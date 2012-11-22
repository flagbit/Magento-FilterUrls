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
 * Packager script to build a magento connect 2.0 packages.
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

    private $_config = null;

    private $_composer = null;

    private $_pathToComposerJson = "";

    /**
     * Run script
     */
    public function run()
    {
        if ($this->getArg('composer')) {
            try {
                $this->_pathToComposerJson = $this->getArg('composer');
                $name = $this->getModuleName();
                $this->getConfig()->setData('name', $name);
                $this->getConfig()->setData('channel', $this->getChannel());
                $this->getConfig()->setData('license', $this->getLicense());
                $this->getConfig()->setData('license_uri', $this->getLicenseUri());
                $this->getConfig()->setData('summary', $this->getDescription());
                $this->getConfig()->setData('description', $this->getDescription());
                $this->getConfig()->setData('version', (string)Mage::getConfig()->getNode()->modules->$name->version);
                $this->getConfig()->setData('stability', $this->getStability());
                $this->getConfig()->setData('authors', $this->getAuthors());
                $this->getConfig()->setData('depends_php_min', $this->getPhpMin());
                $this->getConfig()->setData('depends_php_max', $this->getPhpMax());
                $this->getConfig()->setData('contents', $this->getContent());

                //Packager thinks in context of index.php
                chdir(BP);
                /* @var $package Mage_Connect_Model_Extension */
                $package = Mage::getModel('connect/extension');
                $package->setData($this->getConfig()->getData());
                $package->createPackage();
                echo "Package created at: " . Mage::helper('connect')->getLocalPackagesPath() . PHP_EOL;
            } catch (Exception $e) {
                echo "Errer encountered: " . $e->getMessage() . PHP_EOL;
                echo $e->getTraceAsString() . PHP_EOL;
            }

        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Get JSON file as PHP object.
     *
     * @return mixed|null
     */
    public function getComposerJson()
    {
        if ($this->_composer == null) {
            $fileContent = file_get_contents($this->getPathToComposerJSON());
            $this->_composer = Zend_Json::decode($fileContent, Zend_Json::TYPE_OBJECT);
        }
        return $this->_composer;
    }

    /**
     * Get the config storage.
     *
     * @return null|Varien_Object
     */
    public function getConfig()
    {
        if ($this->_config == null) {
            $this->_config = new Varien_Object();
        }
        return $this->_config;
    }

    /**
     * Get the path to composer json file.
     *
     * @return string
     */
    public function getPathToComposerJSON()
    {
        return $this->_pathToComposerJson;
    }

    /**
     * Parse module name out of composer module name file.
     *
     * @return string
     */
    public function getModuleName()
    {
        $name = $this->getComposerJson()->name;
        $name = join('_', array_map('ucfirst', explode('/', $name)));
        return $name;
    }

    /**
     * Get magento connect channel.
     *
     * @return mixed
     */
    public function getChannel()
    {
        return $this->getComposerJson()->extras->magento_connect->channel;
    }

    /**
     * Get authors.
     *
     * @return array
     */
    public function getAuthors()
    {
        $authors = array("name" => array(), "email" => array(), "user" => array());
        foreach ($this->getComposerJson()->authors as $author) {
            $authors["name"][$author->name] = $author->name;
            $authors["email"][$author->name] = $author->email;
            $authors["user"][$author->name] = strstr(str_replace('.', '_', $author->email), '@', true);
        }
        return $authors;
    }

    /**
     * Get file mappings.
     *
     * @return array
     */
    public function getContent()
    {
        $contents = array("target" => array(), "type" => array(), "path" => array());
        foreach ($this->getComposerJson()->extras->magento_connect->content as $element) {
            $contents["target"][$element->type] = $element->type;
            $contents["type"][$element->type] = $element->structure;
            $contents["path"][$element->type] = $element->path;
        }
        return $contents;
    }

    /**
     * Get license.
     *
     * @return mixed
     */
    public function getLicense()
    {
        return $this->getComposerJson()->license;
    }

    /**
     * Generate license uri.
     *
     * @return string
     */
    public function getLicenseUri()
    {
        return "http://www.spdx.org/licenses/" . $this->getLicense();
    }

    /**
     * Get description for the project.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->getComposerJson()->description;
    }

    /**
     * Get the stability out of composer.
     *
     * @return string
     */
    public function getStability()
    {
        $name = "minimum-stability";
        switch ($this->getComposerJson()->$name) {
            case "dev":
                $stability = "devel";
                break;
            case "alpha":
                $stability = "alpha";
                break;
            case "beta":
                $stability = "beta";
                break;
            case "RC" :
            case "stable":
            default:
                $stability = "stable";
        }
        return $stability;
    }

    /**
     * Minimum PHP version to run the module.
     *
     * @return mixed
     */
    public function getPhpMin()
    {
        return $this->getComposerJson()->extras->magento_connect->php_min;
    }

    /**
     * Maximum PHP version to run the module.
     *
     * @return mixed
     */
    public function getPhpMax()
    {
        return $this->getComposerJson()->extras->magento_connect->php_max;
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
