<?php
/**
 * Created by PhpStorm.
 * User: norman.albusberger
 * Date: 30.12.2014
 * Time: 17:33
 */

return array(
    'navigation' => array(
        'default' => array(

            array(
                'label' => 'Home',
                'route' => 'application/default',
                'controller' => 'Index',
                'action' => 'index',
            ),
            array(
                'label' => 'Submenu Expamle',
                'uri' => '#',
                'pages' => array(
                    array(
                        'label' => 'First  Menu Item',
                        'route' => 'application/default',
                        'controller' => 'Index'
                        
                    ),
                    array(
                        'label' => 'Second Menu Item',
                        'route' => 'application/default',
                        'controller' => 'Index'
                        
                    ),
                    array(
                        'label' => 'Third Menu Item',
                        'route' => 'application/default',
                        'controller' => 'Index'
                        
                    ),
                )
                
            ),

        ),
    )
);