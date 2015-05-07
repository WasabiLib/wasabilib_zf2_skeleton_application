<?php

// module/StickyNotes/config/module.config.php:

return array(
    'controllers' => array(
        'invokables' => array(
            'ReadMe\Controller\ReadMe' => 'ReadMe\Controller\ReadMeController',
        ),

    ),
    'router' => array(
        'routes' => array(
            'readme' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/readme[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ReadMe\Controller\ReadMe',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ReadMe' => __DIR__ . '/../view',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);
