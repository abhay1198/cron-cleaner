<?php
/**
 * Abhay
 * 
 * PHP version 7
 * 
 * @category  Abhay
 * @package   Abhay_CronCleaner
 * @author    Abhay Agrawal <abhay@gmail.com>
 * @copyright 2022 Copyright © Abhay
 * @license   See COPYING.txt for license details.
 * @link      https://github.com/abhay1198/cron-cleaner
 */
namespace Abhay\CronCleaner\Cron;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;

/**
 * Cache Clean class
 */
class Cache
{
    public $helper;

    /**
     * @param Magento\Framework\Model\Context               $context
     * @param Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param Magento\Framework\App\Cache\Frontend\Pool     $cacheFrontendPool
     * @param Abhay\CronCleaner\Helper\Data                 $helper
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Abhay\CronCleaner\Helper\Data $helper
    ) {
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->helper = $helper;
    }

    /**
     * Execute Method
     */
    public function execute()
    {
        $moduleEnabled = (int) $this->helper->isModuleEnabled();
        $cacheEnabled = (int) $this->helper->isCacheEnabled();
        if ($moduleEnabled && $cacheEnabled) {
            $invalidcache = $this->_cacheTypeList->getInvalidated();
            foreach ($invalidcache as $key => $value) {
                $this->_cacheTypeList->cleanType($key);
            }
        }
    }
}
