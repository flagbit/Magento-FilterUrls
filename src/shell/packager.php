<?php
/**
 * This file is part of the Flagbit_FilterUrls project.
 *
 * Flagbit_FilterUrls is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Karl Spies <karl.spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
/**
 * Helper for simple normalization of strings and translation issues
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Karl Spies <karl.spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
require_once 'abstract.php';

/**
 * FilterUrls Packager Shell Script
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Karl Spies <karl.spies@flagbit.de>
 */
class Mage_Shell_Packager extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        /* @var $package Mage_Connect_Model_Extension */
        $package = Mage::getModel('connect/extension');
        $data = new Varien_Object();
        $this->getData('name');
        $this->getData('channel');
        $this->getData('license');
        $this->getData('license_uri');
        $this->getData('summary');
        $this->getData('description');
        $this->getData('version');
        $this->getData('stability');
        $this->getData('authors');

        $package->setData($data->getData());

        $package->generatePackageXml();
        echo $package->getPackageXml();
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f packager.php -- [options]
        php -f packager.php --clean
        php -f packager.php --sitemap --store <store_id>

  create                Create the package
  help                  This help

USAGE;
    }
}

$shell = new Mage_Shell_Packager();
$shell->run();
