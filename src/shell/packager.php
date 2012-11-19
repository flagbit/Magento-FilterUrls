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
        if ($this->getArg('composer')) {
            try {
                /** @var $data Varien_Object */
                $data = $this->parseComposerJson($this->getArg('composer'));
                //Packager thinks in context of index.php
                chdir(BP);
                /* @var $package Mage_Connect_Model_Extension */
                $package = Mage::getModel('connect/extension');
                $package->setData($data->getData());
                echo $package->createPackage();
                echo "Package created at: " . Mage::helper('connect')->getLocalPackagesPath() . PHP_EOL;
            } catch (Exception $e) {
                echo "Errer encountered: " . $e->getMessage() . PHP_EOL;
                echo $e->getTraceAsString() . PHP_EOL;
            }

        } else {
            echo $this->usageHelp();
        }

        $data = new Varien_Object();
        $name = 'Flagbit_FilterUrls';
        $data->setData('name', $name);
        $data->setData('channel', 'community');
        $data->setData('license', 'GPLv3');
        $data->setData('license_uri', 'http://opensource.org/licenses/gpl-3.0');
        $data->setData('summary', 'Eine Zusammenfasssung');
        $data->setData('description', 'Deine Beschreibung');
        //TODO: Version aus dem Modul auslesen
        $data->setData('version', (string)Mage::getConfig()->getNode()->modules->$name->version);
        //TODO: Aus dem Array auslesen aus Mage_Connect_Model_Extension
        $data->setData('stability', 'stable');

        $authors = array();
        $authors["name"] = array("Michael Türk" => "Michael Türk", "Damian Luszczymak" => "Damian Luszczymak", "Karl Spies" => "Karl Spies");
        $authors["email"] = array("Michael Türk" => "Michael.Tuerk@flagbit.de", "Damian Luszczymak" => "damian.luszczymak@flagbit.de", "Karl Spies" => "Karl.Spies@flagbit.de");
        $authors["user"] = array("Michael Türk" => "Michael_Tuerk", "Damian Luszczymak" => "Damian_Luszczymak", "Karl Spies" => "Karl_Spies");
        $data->setData('authors', $authors);
        $data->setData('depends_php_min', "5.3.0");
        $data->setData('depends_php_max', "6.0.0");

        $contents = array();
        //TODO das kann man auch aus dem Name generieren.
        $contents["target"] = array('magecommunity' => 'magecommunity', 'mageetc' => 'mageetc');
        $contents["type"] = array('magecommunity' => 'dir', 'mageetc' => 'file');
        $contents["path"] = array('magecommunity' => 'Flagbit/FilterUrls', 'mageetc' => 'Flagbit_FilterUrls.xml');

        $data->setData('contents', $contents);

    }

    public function parseComposerJson($pathToComposerJSON)
    {
        $fileContent = file_get_contents($pathToComposerJSON);
        $composerConfig = Zend_Json::decode($fileContent);
        $config = new Varien_Object();

        return $config;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f packager.php -- [options]
        php -f packager.php --composer

  composer              Create the package out of composer.json
  help                  This help

USAGE;
    }
}

$shell = new Mage_Shell_Packager();
$shell->run();
