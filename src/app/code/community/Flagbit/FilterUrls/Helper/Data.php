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
* @author Michael Türk <tuerk@flagbit.de>
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
 * @author Michael Türk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Helper_Data extends Mage_Core_Helper_Abstract
{
   /**
	 * normalize Characters
	 * Example: ü -> ue
	 * 
	 * @param string $string
	 * @return string
	 */
	public function normalize($string)
	{
	    $table = array(
	        'Š'=>'S',  'š'=>'s',  'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z',  'ž'=>'z',  'Č'=>'C',  'č'=>'c',  'Ć'=>'C',  'ć'=>'c',
	        'À'=>'A',  'Á'=>'A',  'Â'=>'A',  'Ã'=>'A',  'Ä'=>'Ae', 'Å'=>'A',  'Æ'=>'A',  'Ç'=>'C',  'È'=>'E',  'É'=>'E',
	        'Ê'=>'E',  'Ë'=>'E',  'Ì'=>'I',  'Í'=>'I',  'Î'=>'I',  'Ï'=>'I',  'Ñ'=>'N',  'Ò'=>'O',  'Ó'=>'O',  'Ô'=>'O',
	        'Õ'=>'O',  'Ö'=>'Oe', 'Ø'=>'O',  'Ù'=>'U',  'Ú'=>'U',  'Û'=>'U',  'Ü'=>'Ue', 'Ý'=>'Y',  'Þ'=>'B',  'ß'=>'Ss',
	        'à'=>'a',  'á'=>'a',  'â'=>'a',  'ã'=>'a',  'ä'=>'ae', 'å'=>'a',  'æ'=>'a',  'ç'=>'c',  'è'=>'e',  'é'=>'e',
	        'ê'=>'e',  'ë'=>'e',  'ì'=>'i',  'í'=>'i',  'î'=>'i',  'ï'=>'i',  'ð'=>'o',  'ñ'=>'n',  'ò'=>'o',  'ó'=>'o',
	        'ô'=>'o',  'õ'=>'o',  'ö'=>'oe', 'ø'=>'o',  'ù'=>'u',  'ú'=>'u',  'û'=>'u',  'ý'=>'y',  'þ'=>'b',  'ÿ'=>'y',
	        'Ŕ'=>'R',  'ŕ'=>'r',  'ü'=>'ue', '/'=>'_',  '-'=>'_',  '&'=>'_',  ' '=>'_',  '('=>'_',  ')'=>'_',  '='=>'_',
	    );
	    
	    $string = strtr($string, $table);
        $string = preg_replace('/_[^A-Za-z0-9]|[^A-Za-z0-9]_/U', '_', $string);
        $string = trim($string, '_');
	    
	    return $string;
	}	
}