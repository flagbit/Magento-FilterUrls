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
-- DROP TABLE IF EXISTS {$this->getTable('filterurls/rewrite')};
CREATE TABLE IF NOT EXISTS {$this->getTable('filterurls/rewrite')} (
  `rewrite_id` int(10) unsigned NOT NULL auto_increment,
  `attribute_code` varchar(30) NOT NULL default '',
  `option_id` int(10) unsigned NOT NULL,
  `rewrite` varchar(40) NOT NULL default '',
  `store_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`rewrite_id`),
  CONSTRAINT `UNQ_FILTERURLS_REWRITE_ATTRIBUTECODE_OPTIONID_STOREID` UNIQUE (`attribute_code`, `option_id`, `store_id`),
  CONSTRAINT `UNQ_FILTERURLS_REWRITE_REWRITE_STOREID` UNIQUE (`rewrite`, `store_id`),
  CONSTRAINT `FK_FILTERURLS_REWRITE_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Rewrite values for attribute options' AUTO_INCREMENT=1;
");

$installer->endSetup();

?>