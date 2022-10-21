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
 * @link      https://github.com/abhay1198/
 */
namespace Abhay\CronCleaner\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Helper class
 */
class Data extends AbstractHelper
{
    const CONFIG_MODULE_ENABLE_XPATH 
        = 'cron_cleaner/general/enable';
    const CONFIG_MODULE_FOR_CACHE_ENABLE 
        = 'cron_cleaner/general/for_cache/enable_cache';
    const CONFIG_MODULE_FOR_REINDEX_ENABLE 
        = 'cron_cleaner/general/for_reindex/enable_reindex';
    const CONFIG_MODULE_FOR_LOG_ENABLE 
        = 'cron_cleaner/general/for_log/enable_log';
    const TABLE_NAME = 'cron_cleaner/general/for_log/ranges';    

    public $scopeConfig;
    
    /**
     * @param Magento\Framework\App\Helper\Context              $context
     * @param Magento\Store\Model\StoreManagerInterface         $storeManager,
     * @param Magento\Framework\Serialize\Serializer\Json       $serialize
     * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_storeManager = $storeManager;
        $this->serialize = $serialize;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * Get Module Status
     *
     * @return bool|0|1
     */
    public function isModuleEnabled()
    {
        return (bool)$this->scopeConfig
            ->getValue(self::CONFIG_MODULE_ENABLE_XPATH);
    }
      
    /**
     * Get Cache Enabled Status
     *
     * @return bool|0|1
     */
    public function isCacheEnabled()
    {
        return (bool)$this->scopeConfig
            ->getValue(self::CONFIG_MODULE_FOR_CACHE_ENABLE);
    }

    /**
     * Get Reindex Enabled Status
     *
     * @return bool|0|1
     */
    public function isReindexEnabled()
    {
        return (bool)$this->scopeConfig
            ->getValue(self::CONFIG_MODULE_FOR_REINDEX_ENABLE);
    }

    /**
     * Get Log Enabled Status
     *
     * @return bool|0|1
     */
    public function isLogEnabled()
    {
        return (bool)$this->scopeConfig
            ->getValue(self::CONFIG_MODULE_FOR_LOG_ENABLE);
    }
    
    /**
     * Get Store Id
     *
     * @return storeId
     */
    public function getStoreid()
    {
        return $this->_storeManager->getStore()->getId();
    }

     /**
      * Get table name from store configuration
      *
      * @return $tableNamearray
      */
    public function getTableName()
    {
        $tableNameconfig = $this->scopeConfig->getValue(
            self:: TABLE_NAME,
            ScopeInterface::SCOPE_STORE,
            $this->getStoreid()
        );
        if ($tableNameconfig == '' || $tableNameconfig == null) {
            return;
        }
        $unserializedata = $this->serialize->unserialize($tableNameconfig);
        $tableNamearray = array();
        foreach ($unserializedata as $key => $row) {
            $tableNamearray[] = [
                'db_table_name' => $row['from_qty']
            ]; 
        }
        return $tableNamearray;
    }
}
