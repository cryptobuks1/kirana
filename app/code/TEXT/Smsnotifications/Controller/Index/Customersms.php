<?php
/**
 * @category   Asm
 * @package    Asm_Search
 * @author     sunilnalawade15@gmail.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@g$
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace TEXT\Smsnotifications\Controller\Index;
use Magento\Framework\App\Action\Context;
use \TEXT\Smsnotifications\Helper\Data as Helper;

class Customersms extends \Magento\Framework\App\Action\Action
{

        protected $_helper;

        protected $_customerFactory;

public function __construct(
         \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
         Helper $helper,
         Context $context
 ) {
           $this->_helper  = $helper;
           $this->_customerFactory = $customerFactory;
     parent::__construct($context);
 }


     public function execute()
 {
    // $customerCollection = $this->getCustomerCollection();
     $settings = $this->_helper->getSettings();

     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
     $customerFactory = $objectManager->create('\Magento\Customer\Model\ResourceModel\Customer\CollectionFactory');
           
           $customerCollection = $customerFactory->create()->addFieldToSelect(array('*'));

                 $time = time();
                 $to = date('Y-m-d H:i:s', $time);
                 $lastTime = $time - 300; // 60*60*24
                 $from = date('Y-m-d H:i:s', $lastTime);
                // print_r("to:-".$to);
                // print_r("from:-".$from);exit;
                 $table = "";
		         $table .= "<table style='border:1px solid #000'>";
		         $table .= "<tr style='border:1px solid #000'>";
		         $table .= "<td style='border:1px solid #000;'>";
		         $table .= "Customer";
		         $table .= "</td>";
		         $table .= "<td style='border:1px solid #000'>";
		         $table .= "Telephone";
		         $table .= "</td>";
		         $table .= "<td style='border:1px solid #000'>";
		         $table .= "Status";
		         $table .= "</td>";
		         $table .= "</tr>";


                $customerCollection->addFieldToFilter('created_at', ['lteq' => $to])->addFieldToFilter('created_at', ['gteq' => $from]);
                $result = '';
                foreach ($customerCollection as $customer):
                    // echo "First-->".$customer->getFirstname(); echo "<br/>";
                    // echo "Last-->".$customer->getLastname(); echo "<br/>";
                    // echo "Id-->".$customer->getId(); echo "<br/>";
		    $result = '';
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        $customerNew = $objectManager->create('Magento\Customer\Model\Customer')->load($customer->getId());
                        $telephone = '';

                        if($customerNew->getId() && $customerNew->getPrimaryBillingAddress()['telephone']){
                                $telephone = $customerNew->getPrimaryBillingAddress()->getTelephone();
                                $text = $settings['customer_register'];
                    $admin_recipients[]=$settings['admin_recipients'];
                    array_push($admin_recipients, $telephone);
                    $result = $objectManager->get('TEXT\Smsnotifications\Helper\Data')->sendSms($text,
                    $admin_recipients);
			//print_r($result);
                        }
                        $table .= "<tr style='border:1px solid #000'>";
			            $table .= "<td style='border-right:1px solid #000'>";
			            $table .= $customerNew->getFirstname()." ".$customerNew->getLastname();
			            $table .=  "</td>";
			            $table .= "<td style='border-right:1px solid #000'>";
			            $table .= $telephone;
			            $table .= "</td>";
			            if($result != ''){
			                    $table .=  "<td>";
			                    $table .=  "Sent";
			                    $table .=  "</td>";
			            }else{
			                    $table .=  "<td>";
			                    $table .=  "Fail";
			                    $table .=  "</td>";
			            }
			            $table .= "</tr>";

                endforeach;
               $table .= "</table>";
			   echo $table; 
       // print_r($customerCollection->getData());exit;

    }
}

