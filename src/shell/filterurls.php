<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * Magento Test Shell Script
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Shell_Filterurls extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('clean')) {
            echo "clean";
        } elseif ($this->getArg('sitemap')) {
            $storeId = $this->getArg('store');
            if($storeId == false) {
                echo $this->usageHelp();
                return;
            }
            Mage::app()->setCurrentStore($storeId);
            /* @var $sitemap Flagbit_FilterUrls_Model_Sitemap */
            $sitemap = Mage::getModel('filterurls/sitemap');
            $sitemap->generateXml();
        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f filterurls.php -- [options]
        php -f filterurls.php --clean
        php -f filterurls.php --sitemap --store <store_id>

  clean                 Clean Logs
  sitemap --store <store_id>    Create a sitemap
  help                  This help

USAGE;
    }
}

$shell = new Mage_Shell_Filterurls();
$shell->run();
