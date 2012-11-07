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
 * @author Karl Spies <karl.Spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
/**
 * Parser for given url string. Tries to map the string on attribute options to rebuild the underlying parameters.
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Karl Spies <karl.Spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Model_Request_Info extends Varien_Object
{

    public function _construct()
    {
        $this->setIsValid(false);
        $this->setData('params',array());
    }

    /**
     * Did we find something to parse
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->getData('valid');
    }

    /**
     * Set if request is valid from parser point of view and can be dispatched by the router
     *
     * @param bool $valid
     * @return Flagbit_FilterUrls_Model_Request_Info
     */
    public function setIsValid($valid)
    {
        $this->setData('valid', $valid);
        return $this;
    }

    /**
     * Does their exists a parameter
     * @return bool
     */
    public function hasParams()
    {
        return $this->hasData('params') && count($this->getData('params')) > 0;
    }

    /**
     * Add parameter to the array
     *
     * @param $key
     * @param $value
     * @return Flagbit_FilterUrls_Model_Request_Info
     */
    public function setParam($key, $value)
    {
        $params = $this->getParams();
        $params[$key] = $value;
        $this->setData('params', $params);
        return $this;
    }

    /**
     * Get all paramaters from parsed query
     * @return array
     */
    public function getParams()
    {
        return $this->getData('params');
    }
}
