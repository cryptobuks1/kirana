<?php
namespace Retailinsights\Cartrules\Observer;
use Magento\Framework\Event\ObserverInterface;
   use Magento\Catalog\Api\ProductRepositoryInterfaceFactory as ProductRepository;
    use Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
    use Magento\Store\Model\StoreManagerInterface as StoreManager;
    use Magento\Store\Model\App\Emulation as AppEmulation;
    use Magento\Quote\Api\Data\CartItemExtensionFactory;
    class UpdateUom implements ObserverInterface
    {   
        private $productFactory;

        protected $_productRepository;
       
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
        protected $collection;
        /**
         * @param ProductRepository $productRepository
         * @param \Magento\Catalog\Helper\ImageFactory
         * @param \Magento\Store\Model\StoreManagerInterface
         * @param \Magento\Store\Model\App\Emulation
         * @param CartItemExtensionFactory $extensionFactory
         */
        public function __construct(
            \Magento\Catalog\Model\ProductFactory $productFactory,
            ProductImageHelper $productImageHelper,
            StoreManager $storeManager,
            AppEmulation $appEmulation,
            CartItemExtensionFactory $extensionFactory,
            \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
            \Magento\Quote\Model\ResourceModel\Quote\Item\Collection $collection
        ) {
      
            $this->productImageHelper = $productImageHelper;
            $this->storeManager = $storeManager;
            $this->appEmulation = $appEmulation;
            $this->extensionFactory = $extensionFactory;
            
            $this->_productRepository = $productRepository;
            $this->collection = $collection;
            $this->productFactory = $productFactory;

          
        }
    public function execute(\Magento\Framework\Event\Observer $observer, string $imageType = NULL)
        {

                // $product = $observer->getProduct();
                // $productInfo = $this->_productRepository->getById($product->getEntityId());
                // $optionId = $product->getUnitm();
                // $attribute = $product->getResource()->getAttribute('unitm');
                // if ($attribute->usesSource()) {
                //    $optionText = $attribute->getSource()->getOptionText($optionId); 
                // }
                // $productInfo->setCustomAttribute('uom_label', $optionText);
                // $this->_productRepository->save($productInfo);

        }
    }