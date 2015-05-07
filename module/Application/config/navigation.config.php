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
                'label' => 'ReadMe',
                'route' => 'application/default',
                'controller' => 'Index',
                'action' => 'readme',
            ),
        ),
    )
);