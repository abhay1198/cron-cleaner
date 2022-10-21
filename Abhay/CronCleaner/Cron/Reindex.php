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

/**
 * Reindexer class
 */
class Reindex
{
    public $helper;
    protected $indexFactory;
    protected $indexCollection;

     /**
      * @param Magento\Framework\Model\Context                 $context
      * @param Magento\Indexer\Model\IndexerFactory            $indexFactory
      * @param Magento\Indexer\Model\Indexer\CollectionFactory $indexCollection
      * @param Abhay\CronCleaner\Helper\Data                   $helper
      */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Indexer\Model\IndexerFactory $indexFactory,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexCollection,
        \Abhay\CronCleaner\Helper\Data $helper
    ) {
        $this->indexFactory = $indexFactory;
        $this->indexCollection = $indexCollection;   
        $this->helper = $helper;
    }

    /**
     * Execute Method
     */
    public function execute()
    {
        $moduleEnabled = (int) $this->helper->isModuleEnabled();
        $reindexEnabled  = (int) $this->helper->isReindexEnabled();
        if ($moduleEnabled && $reindexEnabled ) {
            $indexerCollection = $this->indexCollection->create();
            $indexids = $indexerCollection->getAllIds();
            foreach ($indexids as $indexid) {
                $indexidarray = $this->indexFactory->create()->load($indexid);
                $indexidarray->reindexAll($indexid);
            }
        }
    }
}
