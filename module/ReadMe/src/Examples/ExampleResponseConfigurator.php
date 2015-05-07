<?php
/**
 * Created by PhpStorm.
 * User: sascha.qualitz
 * Date: 12.12.14
 * Time: 14:51
 */

namespace ReadMe\Examples;

use WasabiLib\Ajax\GenericMessage;
use WasabiLib\Ajax\GritterMessage;
use WasabiLib\Ajax\ResponseConfigurator;

class ExampleResponseConfigurator extends ResponseConfigurator{
    protected $translator = null;
    public function __construct($translator) {
        $this->translator = $translator;
    }
    public function configure() {
        $message = new GenericMessage("#rcwd_id", "ACTION_TYPE_REPLACE", "innerHtml", array($this->translator->translate("<p style='margin: 0 0 0 0'>I am a new element and I was announced by a notification with a derived ResponseConfigurator.</p>")));
        $gritter = new GritterMessage($this->translator->translate("A new HTML element has appeared! (With a derived ResponseConfigurator.)"), $this->translator->translate("New Element"));
        $gritter->setType(GritterMessage::TYPE_ALERT);

        $this->addResponseType($message);
        $this->addResponseType($gritter);
    }
} 