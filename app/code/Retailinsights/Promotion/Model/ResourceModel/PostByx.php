<?php
namespace Retailinsights\Promotion\Model\ResourceModel;


class PostByx extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('custom_promotion_byX_getY', 'post_id');
	}
	
}
