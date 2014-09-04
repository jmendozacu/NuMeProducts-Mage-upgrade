<?php
/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Adminlogger
 * @version    1.0.2
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */
class Magpleasure_Adminlogger_Model_Actiongroup_Salescreditmemos extends Magpleasure_Adminlogger_Model_Actiongroup_Abstract
{
    protected $_typeValue = 21;

    const ACTION_SALES_CREDITMEMOS_CREATE = 1;
    const ACTION_SALES_CREDITMEMOS_LOAD = 2;
    const ACTION_SALES_CREDITMEMOS_EMAIL = 3;
    const ACTION_SALES_CREDITMEMOS_PRINT = 4;

    public function getLabel()
    {
        return $this->_helper()->__("Sales Credit Memos");
    }

    protected function _getActions()
    {
        return array(
            array('value' => self::ACTION_SALES_CREDITMEMOS_CREATE, 'label' => $this->_helper()->__("Create")),
            array('value' => self::ACTION_SALES_CREDITMEMOS_LOAD, 'label' => $this->_helper()->__("Load")),
            array('value' => self::ACTION_SALES_CREDITMEMOS_EMAIL, 'label' => $this->_helper()->__("Send Email")),
            array('value' => self::ACTION_SALES_CREDITMEMOS_PRINT, 'label' => $this->_helper()->__("Print")),
        );
    }

    public function canDisplayEntity()
    {
        return true;
    }

    public function getModelType()
    {
        return 'sales/order_creditmemo';
    }

    public function getFieldKey()
    {
        return 'increment_id';
    }

    public function getUrlPath()
    {
        return 'adminhtml/sales_creditmemo/view';
    }

    public function getUrlIdKey()
    {
        return 'creditmemo_id';
    }

    public function getFieldPattern()
    {
        return "#%s";
    }
}