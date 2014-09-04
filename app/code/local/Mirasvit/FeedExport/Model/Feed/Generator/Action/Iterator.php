<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Advanced Product Feeds
 * @version   1.1.2
 * @revision  252
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_FeedExport_Model_Feed_Generator_Action_Iterator extends Mirasvit_FeedExport_Model_Feed_Generator_Action
{
    public function process()
    {
        switch ($this->getType()) {
            case 'rule':
                $iteratorModel = Mage::getModel('feedexport/feed_generator_action_iterator_rule');
                break;

            case 'product':
            case 'category':
                $iteratorModel = Mage::getModel('feedexport/feed_generator_action_iterator_entity');
                break;

            default:
                Mage::throwException(sprintf('Undefined iterator type %s', $this->getType()));
                break;
        }

        $iteratorModel
            ->setData($this->getData())
            ->setFeed($this->getFeed());

        if ($iteratorModel->init() === false) {
            $this->finish();
            return;
        }

        $collection = $iteratorModel->getCollection();
        $size       = $collection->getSize();
        $idx        = intval($this->getValue('idx'));

        if ($idx == 0) {
            $this->start();
            $iteratorModel->start();
        }

        $resource   = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_write');
        $collection->getSelect()->limit(0, $idx);
        $stmt       = $connection->query($collection->getSelect());
        $result     = array();
        while ($row = $stmt->fetch()) {
            $callbackResult = $iteratorModel->callback($row);
            if ($callbackResult !== null) {
                $result[] = $callbackResult;
            }
            $idx++;

            $this->setValue('idx', $idx)
                ->setValue('size', $size);

            if (Mage::helper('feedexport')->getState()->isTimeout()) {
                break;
            }
        }

        $iteratorModel->save($result);


        if ($idx == $size) {
            $iteratorModel->finish();
            $this->finish();
        }
    }
}