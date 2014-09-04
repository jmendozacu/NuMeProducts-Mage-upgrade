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
 * @package   Advanced SEO Suite
 * @version   1.0.3
 * @revision  368
 * @copyright Copyright (C) 2014 Mirasvit (http://mirasvit.com/)
 */


class Mirasvit_Seo_Block_Html_Head extends Mage_Page_Block_Html_Head
{
    protected function _construct()
    {
        parent::_construct();
        $this->setupCanonicalUrl();
    }

    public function getConfig()
    {
    	return Mage::getSingleton('seo/config');
    }

    public function getRobots()
    {
        if (!$this->getAction()) {
            return;
        }

    	$fullAction = $this->getAction()->getFullActionName();
        foreach ($this->getConfig()->getNoindexPages() as $page) {
            if (Mage::helper('seo')->checkPattern($fullAction, $page)
                || Mage::helper('seo')->checkPattern(Mage::helper('seo')->getBaseUri(), $page)) {
                return 'NOINDEX,FOLLOW';
            }
        }

        $pages = array_map('strtolower', $this->getConfig()->getNoindexPages());

    	if (in_array(strtolower($fullAction), $pages)) {
    	    return 'NOINDEX,FOLLOW';
    	}

        return parent::getRobots();
    }

    public function setupCanonicalUrl()
    {
    	if (!$this->getConfig()->isAddCanonicalUrl()) {
    		return;
    	}

        if (!$this->getAction()) {
            return;
        }

    	$fullAction = $this->getAction()->getFullActionName();
        foreach ($this->getConfig()->getCanonicalUrlIgnorePages() as $page) {
            if (Mage::helper('seo')->checkPattern($fullAction, $page)
                || Mage::helper('seo')->checkPattern(Mage::helper('seo')->getBaseUri(), $page)) {
                return;
            }
        }

        $productActions = array(
            'catalog_product_view',
            'review_product_list',
            'review_product_view',
            'productquestions_show_index',
        );

        if (in_array($fullAction, $productActions)) {
            $product = Mage::registry('current_product');

            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addFieldToFilter('entity_id', $product->getId())
                ->addStoreFilter()
                ->addUrlRewrite();

            $product      = $collection->getFirstItem();
            $canonicalUrl = $product->getProductUrl();
        } elseif ($fullAction == 'catalog_category_view') {
            $category     = Mage::registry('current_category');
            $canonicalUrl = $category->getUrl();
        } else {
			$canonicalUrl = Mage::helper('seo')->getBaseUri();
			$canonicalUrl = Mage::getUrl('', array('_direct' => ltrim($canonicalUrl, '/')));
            $canonicalUrl = strtok($canonicalUrl, '?');
        }

        //setup crossdomian URL if this option is enabled
        if ($crossDomainStore = $this->getConfig()->getCrossDomainStore()) {
            $url          = Mage::app()->getStore($crossDomainStore)->getBaseUrl();
            $mainHost     = parse_url($url, PHP_URL_HOST);
            $currentHost  = parse_url(Mage::getUrl(), PHP_URL_HOST);            
            $canonicalUrl = str_replace($currentHost, $mainHost, $canonicalUrl);
        }

        $page = Mage::app()->getRequest()->getParam('p');
        if ($page > 1) {
            $canonicalUrl .= "?p=$page";
        }

        $this->addLinkRel('canonical', $canonicalUrl);
    }
}
