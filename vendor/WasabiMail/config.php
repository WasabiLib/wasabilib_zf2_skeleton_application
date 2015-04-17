<?php
return array(
    /*
     * Sets the environment type
     * You can move this to config/autoload/local.php
     */
    "env" => array(
        "type" => "local"
//        "type" => "develop"
        //"type" => "production"
    ),

    "WasabiMail" => array(
        "transporter" => array(
            /**
             * local configuration to save mails as text
             */
            "local" => array(
                "base" => __DIR__,
                "target" => "/localMails/"),

            /**
             * you have a staging or development system with access to a mail server
             */
            "develop" => array(
                "port" => 25,
                "to" => "development@yourdomain.de",
                "name" => "mail.yourmailserver.local",
                "host" =>"mail.yourmailserver.local",),
        ),
    ),
    /**
     * To use email templates you can change the path to your template folder here
     */
    'view_manager' => array(
        'template_path_stack' => array(
            'WasabiMail' => __DIR__ . '/templates',
        ),
    ),
);

