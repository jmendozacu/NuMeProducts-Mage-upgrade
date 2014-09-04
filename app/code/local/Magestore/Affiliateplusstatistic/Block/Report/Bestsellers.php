<?php
class Magestore_Affiliateplusstatistic_Block_Report_Bestsellers extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct(){
		$this->_blockGroup = 'affiliateplusstatistic';
		$this->_controller = 'report_bestsellers';
		$this->_headerText = $this->__('Bestseller Products Report');
		parent::__construct();
		$this->setTemplate('report/grid/container.phtml');
		$this->_removeButton('add');
		$this->addButton('filter_form_submit', array(
			'label'		=> Mage::helper('reports')->__('Show Report'),
			'onclick'	=> 'filterFormSubmit()',
		));
	}
	
	public function getFilterUrl(){
		$this->getRequest()->setParam('filter', null);
		return $this->getUrl('*/*/bestsellers', array('_current' => true));
	}
}