<?php
namespace Retailinsights\Pricerules\Model\ResourceModel\Postthree;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'post_id';
	protected $_eventPrefix = 'retailinsights_pricerules_postthree_collection';
	protected $_eventObject = 'post_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Retailinsights\Pricerules\Model\Postthree', 'Retailinsights\Pricerules\Model\ResourceModel\Postthree');
	}

}
