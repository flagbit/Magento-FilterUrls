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
 * Router that translates FilterUrls URLs into the default Zend_Framework router's version.
 * FilterUrls URLs have a pre-defined structure
 * <category-rewrite-without-suffix>/<option_label_1>-<option_label_2><url-suffix>
 *
 * The router tries to parse the given pathinfo using the parser model and sets the parameters if the parsing was
 * successful. On success the whole request is dispatched and the routing process is complete.
 *
 * @category Flagbit_FilterUrls
 * @package Flagbit_FilterUrls
 * @author Michael Türk <tuerk@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
class Flagbit_FilterUrls_Controller_Router_Filter extends Flagbit_FilterUrls_Controller_Router
{
    const ROUTER_NAME = 'filterurls';

    /**
     * Helper function to register the current router at the front controller.
     *
     * @param Varien_Event_Observer $observer The event observer for the controller_front_init_routers event
     * @event controller_front_init_routers
     */
    public function addUrlRouter($observer)
    {
        $front = $observer->getEvent()->getFront();

        $filterUrlsRouter = new Flagbit_FilterUrls_Controller_Router_Filter();
        $front->addRouter(self::ROUTER_NAME, $filterUrlsRouter);
    }

    /**
     * Get route name
     *
     * @return string
     */
    protected function getRouteName()
    {
        return "catalog";
    }

    /**
     * Get module name
     *
     * @return string
     */
    protected function getModuleName()
    {
        return "Catalog";
    }

    /**
     * Get controller name
     * @return string
     */
    protected function getControllerName()
    {
        return "category";
    }

    /**
     * Get action name
     * @return string
     */
    protected function getActionName()
    {
        return "view";
    }

    /**
     * Get the parser
     *
     * @return Flagbit_FilterUrls_Model_Parser
     */
    protected function getParser()
    {
        return Mage::getModel('filterurls/parser_filter');
    }
}
