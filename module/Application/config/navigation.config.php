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
                'label' => 'Getting-Started',
                'route' => 'application/default',
                'controller' => 'pages',
                'action' => 'gettingStarted',
            ),
            array(
                'label' => 'Examples',
                'route' => 'application/default',
                'controller' => 'pages',
                'action' => 'examples',
            ),
            array(
                'label' => 'Components',
                'route' => 'application/default',
                'controller' => 'pages',
                'action' => 'components',
            ),
        ),
    )
);