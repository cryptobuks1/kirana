<?php
/**
 * Copyright © 2018-2019 Hopescode, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Asm\Setsellerid\Observer;
use Retailinsights\Promotion\Model\PromoTableFactory;
use Magento\Framework\Event\ObserverInterface;
    use Magento\Catalog\Api\ProductRepositoryInterfaceFactory as ProductRepository;

    use Lof\MarketPlace\Model\SellerProductFactory as SellerProduct;

    use Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
    use Magento\Store\Model\StoreManagerInterface as StoreManager;
    use Magento\Store\Model\App\Emulation as AppEmulation;
    use Magento\Quote\Api\Data\CartItemExtensionFactory;
    use Magento\Quote\Api\Data\CartExtensionFactory;


    class ProductInterface implements ObserverInterface
    {   
         /**
         * @var SellerProduct
         */
        protected $sellerProduct;

        /**
         * @var ObjectManagerInterface
         */
        protected $_objectManager;

        /**
         * @var ProductRepository
         */
        protected $productRepository;

        /**
         *@var \Magento\Catalog\Helper\ImageFactory
         */
        protected $productImageHelper;

        /**
         *@var \Magento\Store\Model\StoreManagerInterface
         */
        protected $storeManager;

        /**
         *@var \Magento\Store\Model\App\Emulation
         */
        protected $appEmulation;

        /**
         * @var CartItemExtensionFactory
         */
        protected $extensionFactory;

        protected $cartExtFactory;
  protected $_promoFactory;
  protected $_connection;
  protected $quoteItemFactory;


        /**
         * @param \Magento\Framework\ObjectManagerInterface $objectManager
         * @param ProductRepository $productRepository
          * @param SellerProduct $sellerProduct

         * @param \Magento\Catalog\Helper\ImageFactory
         * @param \Magento\Store\Model\StoreManagerInterface
         * @param \Magento\Store\Model\App\Emulation
         * @param CartItemExtensionFactory $extensionFactory
         */
        public function __construct(
            \Magento\Framework\ObjectManagerInterface $objectManager,
            \Magento\Framework\App\ResourceConnection $_connection,
            PromoTableFactory $promoFactory,
            SellerProduct $sellerProduct,
            ProductRepository $productRepository,
            ProductImageHelper $productImageHelper,
            StoreManager $storeManager,
            AppEmulation $appEmulation,
            CartItemExtensionFactory $extensionFactory,
            CartExtensionFactory $cartExtFactory,
            \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory
        ) {
            $this->_objectManager = $objectManager;
            $this->productRepository = $productRepository;
            $this->productImageHelper = $productImageHelper;
            $this->storeManager = $storeManager;
            $this->appEmulation = $appEmulation;
            $this->extensionFactory = $extensionFactory;
            $this->cartExtFactory = $cartExtFactory;
            $this->sellerProduct = $sellerProduct;
        $this->_promoFactory = $promoFactory;        
        $this->_connection = $_connection;
        $this->quoteItemFactory = $quoteItemFactory;
        }

        public function execute(\Magento\Framework\Event\Observer $observer, string $imageType = NULL)
            {
$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/pvn.log'); 
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
// $logger->info('Product Interface');
            $doorStepPrice=0;
            $pickupFrmStorePrice=0;
            $PickupFromStore=0;
            $PickupFromNearbyStore=0;
            $discount_amount = 0;

            $door=0;
            $price =0;

            $doorStepPId = 0;
            $pickupFrmStorePId = 0;
       
            $quote = $observer->getQuote();
            
            $subTotal = 0;
            foreach ($quote->getAllItems() as $quoteItem) {

                $product = $this->productRepository->create()->getById($quoteItem->getProductId());
             $freeQty = 0; $freeProduct =  0; $freeSku = 0;
                $discountData = $this->_promoFactory->create()->getCollection()
                ->addFieldToFilter('cart_id', $quoteItem->getQuoteId());
                if(isset($discountData)){
                    foreach($discountData->getData() as $k => $val){ 
                        $discount_amount = $val['total_discount'];
                        $itemInfo = json_decode($val['item_qty'],true);
                        foreach($itemInfo as $k => $itemArray){
                          foreach($itemArray as $key => $value){
                            $itemData = json_decode($value);
                            if(isset($itemData->id)){
                                if($itemData->id == $quoteItem->getItemId()) {
                                    $freeQty = $itemData->qty;
                                }
                            }
                            if(isset($itemData->parent)){
                                if($itemData->parent == $quoteItem->getItemId()) {
                                     $freeQuoteItem = $this->quoteItemFactory->create()->load($itemData->id);
                                     $freeSku = $freeQuoteItem->getSku();
                                     $freeProduct = $itemData->id;
                                }
                            }

                          }
                        }
                    }
                }

                $SellerProd = $this->sellerProduct->create()->getCollection();
                $fltColl = $SellerProd->addFieldToFilter('seller_id', $quoteItem['seller_id'])
                        ->addFieldToFilter('product_id', $quoteItem->getProductId());
                $idInfo = $fltColl->getData();
                if(!empty($idInfo)){
                        foreach($idInfo as $info){
                            $id = $info['entity_id'];
                            $data = $this->sellerProduct->create()->load($id);
                            $door = $data->getDoorstepPrice();
                            $PickupFromStore= $data->getPickupFromStore();
                            $PickupFromNearbyStore= $data->getPickupFromNearbyStore();
                        }
                }
                if($quoteItem->getPriceType() == 0){
                    $doorStepPId += $quoteItem->getQty();
                    $rowPrice = $door * $quoteItem->getQty();
                    $doorStepPrice += $rowPrice;
                } else if($quoteItem->getPriceType() == 1) {
                    $pickupFrmStorePId += $quoteItem->getQty();
                    $rowPrice = $PickupFromStore * $quoteItem->getQty();
                    $pickupFrmStorePrice += $rowPrice;
                }
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $this->_connection->getConnection();
                
                $quoteQry  = "SELECT * FROM `mgquote_item` WHERE item_id ='".$quoteItem->getItemId()."'" ;
                $Result 	= $connection->rawFetchRow($quoteQry);
                $logger->info('Query QTy  '.$Result['qty']);

            

                $uom = $product->getUnitm();
                $optionId = $product->getUnitm();
                $weight = round($product->getWeight(), 0);
                $attribute = $product->getResource()->getAttribute('unitm');
                if ($attribute->usesSource()) {
                    $optionText = $weight." ".$attribute->getSource()->getOptionText($optionId);
                }

                $itemExtAttr = $quoteItem->getExtensionAttributes();

                if ($itemExtAttr === null) {
                    $itemExtAttr = $this->extensionFactory->create();
                } 
                
                $imageurl =$this->productImageHelper->create()->init($product, 'product_thumbnail_image')->setImageFile($product->getThumbnail())->getUrl();

                $itemExtAttr->setUnitm($optionText);
                $itemExtAttr->setExtRowQty($Result['qty']);
                $itemExtAttr->setExtFreeQty($freeQty);
                $itemExtAttr->setExtFreeProduct($freeProduct);
                $itemExtAttr->setSku($freeSku);

                $itemExtAttr->setVolume($product->getVolume());
                if(!empty($idInfo)){
                $itemExtAttr->setDoorstepPrice($door);
                $itemExtAttr->setPickupFromStore($PickupFromStore);
                $itemExtAttr->setPriceType($quoteItem['price_type']);
                $itemExtAttr->setPickupFromNearbyStore($PickupFromNearbyStore);
                
                $itemExtAttr->setImageUrl($imageurl);
                $itemExtAttr->setExtRowTotal($rowPrice);
                $quoteItem->setExtensionAttributes($itemExtAttr);
    
                }
             
            }
            $itemExtAttrquote = $quote->getExtensionAttributes();
            if ($itemExtAttrquote === null) {
                    $itemExtAttrquote = $this->cartExtFactory->create();
                } 
                $itemExtAttrquote->setDsCount($doorStepPId);
            $itemExtAttrquote->setDsSubtotal($doorStepPrice);
            $itemExtAttrquote->setSpCount($pickupFrmStorePId);
            $itemExtAttrquote->setSpSubtotal($pickupFrmStorePrice - $discount_amount);
                $quote->setExtensionAttributes($itemExtAttrquote);

         return;

        }

        /**
         * Helper function that provides full cache image url
         * @param \Magento\Catalog\Model\Product
         * @return string
         */
        protected function getImageUrl($product, string $imageType = NULL)
        {
            $storeId = $this->storeManager->getStore()->getId();

            $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
            $imageUrl = $this->productImageHelper->create()->init($product, $imageType)->getUrl();

            $this->appEmulation->stopEnvironmentEmulation();

            return $imageUrl;
        }

    }
