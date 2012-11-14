<?php
/**
* This file is part of the Flagbit_FilterUrls project.
* Install Script for filterurls rewrite table.
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
* @author Michael Türk <michael.tuerk@flagbit.de>
* @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
* @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
* @version 0.1.0
* @since 0.1.0
*/
$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('filterurls/url')};
CREATE TABLE IF NOT EXISTS {$this->getTable('filterurls/url')} (
  `url_id` int(10) unsigned NOT NULL auto_increment,
  `request_path` varchar(255) NOT NULL default '',
  `category_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `attributes` VARCHAR(255) NULL DEFAULT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`url_id`),
  UNIQUE INDEX `UNQ_FILTERURLS_REQUEST_PATH_STORE_ID` (`request_path`, `store_id`),
  CONSTRAINT `FK_FILTERURLS_URL_CATEGORY` FOREIGN KEY (`category_id`) REFERENCES `{$this->getTable('catalog/category')}` (`entity_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Generated URLs for rewrite combinations' AUTO_INCREMENT=1;
");

$installer->endSetup();

?>
