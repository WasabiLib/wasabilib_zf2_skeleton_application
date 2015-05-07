<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use WasabiLib\Wizard\StorageContainer;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        $config = array();
        $configFiles = array(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/navigation.config.php'
        );
        foreach ($configFiles as $file) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, $file);
        }
        return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'SessionManager' => function ($sm) {
                        $sessionConfig = new SessionConfig();
                        $sessionConfig->setOption('remember_me_seconds', 5);

                        $sessionManager = new SessionManager($sessionConfig);
                        \Zend\Session\Container::setDefaultManager($sessionManager);

                        return $sessionManager;
                    }, 'StorageContainer' => function ($sm) {
                        $storageContainer = new StorageContainer("wizard");
//                        $storageContainer->clearStorage();
                        return $storageContainer;
                    }
            ),
        );
    }
}
