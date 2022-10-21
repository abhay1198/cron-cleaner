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
use \Psr\Log\LoggerInterface;

/**
 * Log Clean class
 */
class Log
{
    public $helper;
    protected $logger;
    protected $resourceConnection;

    /**
     * @param Magento\Framework\Model\Context          $context
     * @param Magento\Framework\App\ResourceConnection $resourceConnection
     * @param Abhay\CronCleaner\Helper\Data            $helper
     * @param LoggerInterface                          $logger
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Abhay\CronCleaner\Helper\Data $helper,
        LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * Execute Method
     */
    public function execute()
    {
        $moduleEnabled = (int) $this->helper->isModuleEnabled();
        $logEnabled = (int) $this->helper->isLogEnabled();
        try{
            if ($moduleEnabled && $logEnabled) {
                $connection  = $this->resourceConnection->getConnection();
                $tablesForClean = array(
                    'customer_log',
                    'customer_visitor',
                    'report_compared_product_index',
                    'report_viewed_product_aggregated_daily',
                    'report_viewed_product_aggregated_monthly',
                    'report_viewed_product_aggregated_yearly',
                    'report_viewed_product_index'
                );
                foreach ($tablesForClean as $value) {
                    $tableName = $this->resourceConnection
                        ->getTableName($value);
                    $sql = "TRUNCATE ".$tableName;
                    $connection->query($sql);
                } 
                $customtable = array();
                $customtable = $this->helper->getTableName();
                if (isset($customtable)) {
                    foreach ($customtable as $subarray) {
                        foreach ($subarray as $value) {
                            $tableName  = $this->resourceConnection
                                ->getTableName($value);
                            $sql = "TRUNCATE ".$tableName;
                            $connection->query($sql);
                        }
                    }
                }
            }
        } catch(\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
