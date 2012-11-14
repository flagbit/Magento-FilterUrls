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
 * @author Karl Spies <Karl.Spies@flagbit.de>
 * @copyright 2012 Flagbit GmbH & Co. KG (http://www.flagbit.de). All rights served.
 * @license http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 * @version 0.1.0
 * @since 0.1.0
 */
abstract class Flagbit_FilterUrls_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * Helper function to register the current router at the front controller.
     *
     * @param Varien_Event_Observer $observer The event observer for the controller_front_init_routers event
     * @event controller_front_init_routers
     */
    abstract public function addUrlRouter($observer);

    /**
     * Get route name
     *
     * @return string
     */
    abstract protected function getRouteName();

    /**
     * Get module name
     *
     * @return string
     */
    abstract protected function getModuleName();

    /**
     * Get controller name
     * @return string
     */
    abstract protected function getControllerName();

    /**
     * Get action name
     * @return string
     */
    abstract protected function getActionName();

    /**
     * Get the corresponding parser to analyze the url
     * @return Flagbit_FilterUrls_Model_Parser
     */
    abstract protected function getParser();


    /**
     * Rewritten function of the standard controller. Tries to match the pathinfo on url parameters.
     *
     * @see Mage_Core_Controller_Varien_Router_Standard::match()
     * @param Zend_Controller_Request_Http $request The http request object that needs to be mapped on Action Controllers.
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), ' /');

        $parsedRequestInfo = $this->getParser()->parseFilterInformationFromRequest($request, Mage::app()->getStore()->getId());

        if (!$parsedRequestInfo->isValid()) {
            return false;
        }

        // if successfully gained url parameters, use them and dispatch ActionController action
        $request->setRouteName($this->getRouteName())
            ->setModuleName(strtolower($this->getModuleName()))
            ->setControllerName($this->getControllerName())
            ->setActionName($this->getActionName());

        $pathInfo = strtolower($this->getModuleName()) . '/' . $this->getControllerName() . '/' . $this->getActionName();
        $requestUri = '/' . $pathInfo;

        if ($parsedRequestInfo->hasParams()) {
            $requestUri = $requestUri . '/';
            foreach ($parsedRequestInfo->getParams() as $paramKey => $paramValue) {
                $request->setParam($paramKey, $paramValue);
                $requestUri .= $paramKey . '/' . $paramValue . '/';
            }
        }

        $controllerClassName = $this->_validateControllerClassName('Mage_' . $this->getModuleName(), $this->getControllerName());
        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $this->getFront()->getResponse());

        $request->setRequestUri(rtrim($requestUri, ' /'));
        $request->setPathInfo($pathInfo);

        // dispatch action
        $request->setDispatched(true);
        $controllerInstance->dispatch($this->getActionName());

        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );
        return true;
    }
}
