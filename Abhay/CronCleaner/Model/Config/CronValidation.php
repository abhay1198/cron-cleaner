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
namespace Abhay\CronCleaner\Model\Config;

use Magento\Framework\Exception\LocalizedException;

/**
 * Check Input Validation in System Configuration
 */
class CronValidation extends \Magento\Framework\App\Config\Value
{
    /**
     * Check pattern
     */
    public function beforeSave()
    {
        $value     = $this->getValue();
        $validator = \Zend_Validate::is(
            $value,
            'Regex',
            ['pattern' => '/^[0-9,\-\?\/\*\ ]+$/']
        );

        if (!$validator) {
            $message = __(
                'Please correct Cron Expression: "%1".',
                $value
            );
            throw new LocalizedException($message);
        }

        return $this;
    }
}
