<?php
namespace WasabiMail;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module implements AutoloaderProviderInterface, ViewHelperProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig() {
        // TODO: Implement getViewHelperConfig() method.
    }

    public function getServiceConfig(){
        return array(
            'factories' => array("Mail" => function($sm) {
                $mail = new Mail();
                $mail->setRenderer($sm->get("ViewRenderer"));
                /**
                 * @var $sm \Zend\ServiceManager\ServiceLocatorInterface
                 */
                $config = $sm->get("config");
                $env = $config["env"]["type"];
                $transporterConfig = $config["WasabiMail"]["transporter"];

                $transporter = null;
                //Production
                if ($env=="production") {
                    $transporter = new Sendmail();
                }
                //Develop or Staging
                elseif($env=="develop"){
                    $mailConfig = $transporterConfig["develop"];
                    $mail->setTo($mailConfig["to"]);
                    $transporter = new \Zend\Mail\Transport\Smtp();
                    $options = new \Zend\Mail\Transport\SmtpOptions();
                    $options->setName($mailConfig["name"]);
                    $options->setHost($mailConfig["host"]);
                    $options->setPort($mailConfig["port"]);

                    $transporter->setOptions($options);
                }
                //Local development mode - write to disk
                elseif($env=="local") {
                    $fileConfig = $transporterConfig["local"];
                    $options = new \Zend\Mail\Transport\FileOptions();
                    $options->setPath( $fileConfig["base"].$fileConfig["target"]);
                    $options->setCallback(
                        function(\Zend\Mail\Transport\File $transport){
                            return "Message_".microtime(true)."-".mt_rand(0,100).".txt";
                        }
                    );
                    $transporter = new \Zend\Mail\Transport\File($options);
                }
                $mail->setTransporter($transporter);
                return $mail;
            })
        );
    }
}