<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\StepOneForm;
use Application\Form\StepOneFormValidator;
use Application\Form\StepTwoForm;
use Application\Form\StepTwoFormValidator;
use WasabiLib\Ajax\GritterMessage;
use WasabiLib\Ajax\InnerHtml;
use WasabiLib\Ajax\Response;
use WasabiLib\Modal\WasabiModal;
use WasabiLib\Modal\WasabiModalView;
use WasabiLib\Wizard\StepCollection;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\Service\FlashMessengerFactory;
use Zend\View\Model\ViewModel;
use WasabiLib\Ajax\Alert;
use WasabiLib\Ajax\ConsoleLog;
use WasabiLib\Ajax\DomManipulator;
use WasabiLib\Ajax\GenericMessage;
use WasabiLib\Ajax\Redirect;
use WasabiLib\Ajax\ResponseConfigurator;
use WasabiLib\Ajax\TriggerEventManager;
use WasabiLib\Modal\Button;
use WasabiLib\Modal\Dialog;
use WasabiLib\Modal\Info;
use WasabiLib\Mail;
use Application\Examples\ExampleResponseConfigurator;

class PagesController extends AbstractActionController
{
    public function indexAction()
    {

        return new ViewModel();
    }

    public function startExampleAction(){
        $innerHtml = new InnerHtml("#start_example_target_id","This comes right from the Server");
        return $this->getResponse()->setContent(new Response($innerHtml));
    }
    public function examplesAction(){

    }
    public function componentsAction(){

    }
    public function gettingStartedAction(){

    }

    public function openModalAction(){
        $modal = new WasabiModal("Ich bin ein Modal","Mein Modal Inhalt");
        $modalView = new WasabiModalView("#modalplace",$this->getServiceLocator()->get('ViewRenderer'),$modal);
        $message = new GritterMessage("Hallo","Was stimmt nicht mit Dir?");
        $message->setType(GritterMessage::TYPE_SUCCESS);
        $ajaxResponse = new Response();
        $ajaxResponse->add($modalView);
        $ajaxResponse->add($message);
        return $this->getResponse()->setContent($ajaxResponse);
    }

    // ----------------------------- EXAMPLES SECTION ----------------------------//
    public function redirectAction() {
        $redirect = new Redirect("/application/pages/gettingStarted");

        $response = new Response();
        $response->add($redirect);

        return $this->getResponse()->setContent($response);
    }

    public function alertAction() {
        $alert = new Alert($this->getServiceLocator()->get('translator')->translate("This is important!"));

        $response = new Response();
        $response->add($alert);

        return $this->getResponse()->setContent($response);
    }

    public function modalAction() {
        $type = $this->params()->fromQuery("type");
        $response = new Response();

        if($type == "dialog") {
            $modalConf = new Dialog("Dialog", "Accept or Decline", $this->getServiceLocator()->get('translator')->translate("Do you really want to abort this action?"), Dialog::TYPE_SUCCESS);
            $modalConf->getConfirmButton()->setButtonText($this->getServiceLocator()->get('translator')->translate("accept"));
            $modalConf->getDismissButton()->setButtonText($this->getServiceLocator()->get('translator')->translate("decline"));
        } elseif($type == "info") {
            $modalConf = new Info("Info", "Saved", $this->getServiceLocator()->get('translator')->translate("Your settings has been saved successfully."));
        } else {
            $modalConf = new WasabiModal("Standard", "Standard Window");
            $viewModel = new ViewModel();
            $viewModel->setTemplate("examples/examples/standardModalExample.phtml");
            $modalConf->setContent($viewModel);

            $button = new Button($this->getServiceLocator()->get('translator')->translate('More Info (without Ajax)'));
            $button->setAction("standardModalInfo", false);

            $modalConf->addButton($button);

            $ajaxButton = new Button($this->getServiceLocator()->get('translator')->translate('More Info (with Ajax)'));
            $ajaxButton->setAction("standardModalWithAjaxInfo");

            $modalConf->addButton($ajaxButton);
        }
        $modalConf->addClass("wasabi");

        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $modalConf);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function standardSizeAction() {
        $size = $this->params()->fromQuery("size");
        $response = new Response();

        $standard = new WasabiModal("Standard", "Standard Window");
        $standard->setSize($size);
        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standardModalExample.phtml");
        $standard->setContent($viewModel);
        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $standard);

        $button = new Button($this->getServiceLocator()->get('translator')->translate('More Info'));
        $button->setAction("standardModalInfo", false);

        $standard->addButton($button);

        $ajaxButton = new Button($this->getServiceLocator()->get('translator')->translate('More Info (with Ajax)'));
        $ajaxButton->setAction("standardModalWithAjaxInfo");

        $standard->addButton($ajaxButton);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function standardCssAction() {
        $class = $this->params()->fromQuery("class");
        $response = new Response();

        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standardModalExample.phtml");

        $standard = new WasabiModal("Standard", "Standard Window");
        $standard->addClass($class);
        $standard->setContent($viewModel);

        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $standard);

        $button = new Button($this->getServiceLocator()->get('translator')->translate('More Info'));
        $button->setAction("standardModalInfo", false);

        $standard->addButton($button);

        $ajaxButton = new Button($this->getServiceLocator()->get('translator')->translate('More Info (with Ajax)'));
        $ajaxButton->setAction("standardModalWithAjaxInfo");

        $standard->addButton($ajaxButton);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function firstOfThreeAction() {
        $response = new Response();

        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standardModalExample.phtml");

        $standard = new WasabiModal("Standard", "Standard Window");

        $button = new Button($this->getServiceLocator()->get('translator')->translate('More Info'));
        $button->setAction("secondOfThree");

        $standard->addButton($button);

        $standard->setContent($viewModel);
        $modal = new WasabiModalView("#wasabi_modal",
            $this->getServiceLocator()->get("ViewRenderer"), $standard);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function secondOfThreeAction() {
        $response = new Response();

        $modalConf = new Dialog("Dialog", "Accept or Decline", $this->getServiceLocator()->get('translator')->translate("Do you really want to abort this action?"), Dialog::TYPE_SUCCESS);
        $modalConf->setIcon("fa-question");
        $modalConf->getConfirmButton()->setButtonText($this->getServiceLocator()->get('translator')->translate("Abort"));
        $modalConf->getConfirmButton()->isNoDismissButton();
        $modalConf->getConfirmButton()->setAction("thirdOfThree");
        $modalConf->getDismissButton()->setButtonText($this->getServiceLocator()->get('translator')->translate("decline"));

        $modalConf->addClass("wasabi");

        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $modalConf);
        $modal->setActionType(InnerHtml::ACTION_TYPE_APPEND);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function thirdOfThreeAction() {
        $response = new Response();

        $modalConf = new Info("Info", "Saved", $this->getServiceLocator()->get('translator')->translate("You've aborted this action successfully."));

        $modalConf->addClass("wasabi");

        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $modalConf);
        $modal->setActionType(InnerHtml::ACTION_TYPE_APPEND);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function standardCloseAction() {
        $response = new Response();

        $standard = new WasabiModal("Standard", "Standard Modal");

        // Use a customized close button
        $closeButton = new Button("finish");
        $closeButton->setAction("standardFinish");
        $closeButton->isDismissButton();

        $standard->addButton($closeButton);

        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standardModalExample.phtml");
        $standard->setContent($viewModel);
        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $standard);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function standardFinishAction() {
        $response = new Response();

        $innerHtml = new InnerHtml("#close_modal_id1", "The modal window is closed.", InnerHtml::ACTION_TYPE_APPEND);

        $response->add($innerHtml);

        return $this->getResponse()->setContent($response);
    }

    public function standardBackdropAction() {
        $backdropOption = $this->params()->fromQuery("backdrop");
        $response = new Response();

        $standard = new WasabiModal("Standard", "Standard Window");
        $standard->setBackdrop($backdropOption);
        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standardModalExample.phtml");
        $standard->setContent($viewModel);
        $modal = new WasabiModalView("#wasabi_modal", $this->getServiceLocator()->get("ViewRenderer"), $standard);

        $button = new Button($this->getServiceLocator()->get('translator')->translate('More Info'));
        $button->setAction("standardModalInfo", false);

        $standard->addButton($button);

        $ajaxButton = new Button($this->getServiceLocator()->get('translator')->translate('More Info (with Ajax)'));
        $ajaxButton->setAction("standardModalWithAjaxInfo");

        $standard->addButton($ajaxButton);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function standardAnimationAction() {
        $animation = $this->params()->fromQuery("animation");
        $response = new Response();

        $standard = new WasabiModal("Standard", "Standard Window");
        // set animation option
        $standard->setAnimationType($animation);

        $button = new Button('More Info');
        $button->setAction("standardModalInfo", false);

        $standard->addButton($button);

        $ajaxButton = new Button('More Info (with Ajax)');
        $ajaxButton->setAction("standardModalWithAjaxInfo");

        $standard->addButton($ajaxButton);

        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standardModalExample.phtml");
        $standard->setContent($viewModel);
        $modal = new WasabiModalView("#wasabi_modal",
            $this->getServiceLocator()->get("ViewRenderer"), $standard);

        $response->add($modal);

        return $this->getResponse()->setContent($response);
    }

    public function standardModalInfoAction() {
        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standard-modal-info.phtml");
        return $viewModel;
    }

    public function standardModalWithAjaxInfoAction() {
        $response = new Response();

        $viewModel = new ViewModel();
        $viewModel->setTemplate("examples/examples/standard-modal-info.phtml");

        $innerHtml = new InnerHtml("#wasabi-modal,.modal-body", null, InnerHtml::ACTION_TYPE_REPLACE, $this->getServiceLocator()->get("ViewRenderer"));
        $innerHtml->setViewModel($viewModel);

        $response->add($innerHtml);
        return $this->getResponse()->setContent($response);
    }

    public function shortSimpleAction(){
        $shortMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $shortMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a simple message with text only."));
        $shortMessage->setText($this->getServiceLocator()->get('translator')->translate("Simple Notification"));

        $response = new Response();
        $response->add($shortMessage);
        return $this->getResponse()->setContent($response);
    }

    public function shortFAAction(){
        $shortMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $shortMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a message with a Font Awesome Icon."));
        $shortMessage->setText($this->getServiceLocator()->get('translator')->translate("Notification with FA"));
        $shortMessage->setIcon("envelope");

        $response = new Response();
        $response->add($shortMessage);
        return $this->getResponse()->setContent($response);
    }

    public function shortTypedAction(){
        $infoMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $infoMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a blue info message with a text and a Font Awesome icon."));
        $infoMessage->setText($this->getServiceLocator()->get('translator')->translate("Info Notification"));
        $infoMessage->setTime(2400);
        $infoMessage->setType(GritterMessage::TYPE_INFO);

        $successMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $successMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a green success message with a text and a Font Awesome icon."));
        $successMessage->setText($this->getServiceLocator()->get('translator')->translate("Success Notification"));
        $successMessage->setTime(2600);
        $successMessage->setType(GritterMessage::TYPE_SUCCESS);

        $errorMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $errorMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a red error message with text and a Font Awesome icon."));
        $errorMessage->setText($this->getServiceLocator()->get('translator')->translate("Error Notification"));
        $errorMessage->setTime(2800);
        $errorMessage->setType(GritterMessage::TYPE_ERROR);

        $alertMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $alertMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is an orange alert message with text and a Font Awesome icon."));
        $alertMessage->setText($this->getServiceLocator()->get('translator')->translate("Alert Notification"));
        $alertMessage->setType(GritterMessage::TYPE_ALERT);

        $response = new Response();
        $response->add($infoMessage);
        $response->add($successMessage);
        $response->add($errorMessage);
        $response->add($alertMessage);
        return $this->getResponse()->setContent($response);
    }

    public function shortImageAction() {
        $shortMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $shortMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a info message with text, and an image."));
        $shortMessage->setText($this->getServiceLocator()->get('translator')->translate("Notification with an Image"));
        $shortMessage->setImage("http://johnjournal.bravesites.com/files/images/Profile_Score_Photo.jpg");

        $response = new Response();
        $response->add($shortMessage);
        return $this->getResponse()->setContent($response);
    }

    public function shortPositionsAction() {
        $position = $this->params()->fromQuery("position");
        $shortMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $shortMessage->setTitle($this->getServiceLocator()->get('translator')->translate("This is a message at different positions."));
        $shortMessage->setText($this->getServiceLocator()->get('translator')->translate("Notification at different positions."));
        $shortMessage->setPosition($position);

        switch($position) {
            case GritterMessage::POSITION_TOP_RIGHT:
                $newPosition = GritterMessage::POSITION_TOP_LEFT;
                break;
            case GritterMessage::POSITION_TOP_LEFT:
                $newPosition = GritterMessage::POSITION_BOTTOM_LEFT;
                break;
            case GritterMessage::POSITION_BOTTOM_LEFT:
                $newPosition = GritterMessage::POSITION_BOTTOM_RIGHT;
                break;
            default:
                $newPosition = GritterMessage::POSITION_TOP_RIGHT;
                break;
        }

        $changeNextPosition = new DomManipulator("#btn5", "data-json", '{"position":"'.$newPosition.'"}', DomManipulator::ACTION_TYPE_ATTR);
        $response = new Response();
        $response->add($shortMessage);
        $response->add($changeNextPosition);
        return $this->getResponse()->setContent($response);
    }

    public function shortStickyAndTimeAction() {
        $messageSticky = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $messageSticky->setTitle($this->getServiceLocator()->get('translator')->translate("Permanent Notification"));
        $messageSticky->setText($this->getServiceLocator()->get('translator')->translate("This is a message which will not disappear."));
        $messageSticky->setSticky(true);

        $fiveSeconds = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $fiveSeconds->setTitle($this->getServiceLocator()->get('translator')->translate("Notification with 5 seconds dwell time"));
        $fiveSeconds->setText($this->getServiceLocator()->get('translator')->translate("This is a message which will disappear in 5 seconds."));
        $fiveSeconds->setTime(5000);

        $response = new Response();
        $response->add($messageSticky);
        $response->add($fiveSeconds);
        return $this->getResponse()->setContent($response);
    }

    public function genericMessageAction() {
        // Any class with type GenericMessage or ResponseConfigurator or subclasses
        $message = new GenericMessage("#target_id", "ACTION_TYPE_REPLACE", "innerHtml", "InnerHtml", array($this->getServiceLocator()->get('translator')->translate("<p style='margin: 0 0 0 0'>I am injected during an AJAX request.</p>")));

        $response = new Response();
        $response->add($message);

        return $this->getResponse()->setContent($response);
    }

    public function genericMessageChangeColorAction() {
        // Any class with type GenericMessage or ResponseConfigurator or subclasses
        $message = new GenericMessage("#gm_btn", "ACTION_TYPE_CSS", "domManipulator", "DomManipulator", array("background-color", "red"));

        $response = new Response();
        $response->add($message);

        return $this->getResponse()->setContent($response);
    }

    public function useAjaxButtonAction() {
        $params = $this->params()->fromQuery();

        $numberOfParams = count($params);

        $paramsAsString = "";
        $i = 0;
        foreach($params as $key => $value) {
            $i++;
            $paramsAsString .= "the ".$i.". parameter pair is: ".$key." = ".$value.", ";
        }

        $paramsAsString = ucfirst(trim($paramsAsString, ','));

        // Any class with type GenericMessage or ResponseConfigurator or subclasses
        $message = new GenericMessage("#ajax_btn_id", "ACTION_TYPE_REPLACE", "innerHtml", "InnerHtml", array("You send the following ".$numberOfParams." parameters: ".$paramsAsString));

        $response = new Response();
        $response->add($message);

        return $this->getResponse()->setContent($response);
    }

    public function responseConfiguratorWithoutDerivingAction() {
        $message = new GenericMessage("#rcwod_id", "ACTION_TYPE_REPLACE", "innerHtml", "InnerHtml", array($this->getServiceLocator()->get('translator')->translate("<p style='margin: 0 0 0 0'>I am a new element and I was announced by a notification.</p>")));
        $shortMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $shortMessage->setTitle($this->getServiceLocator()->get('translator')->translate("A new element has appeared! (Without a derived Response Configurator.)"));
        $shortMessage->setText($this->getServiceLocator()->get('translator')->translate("New Element"));
        $shortMessage->setType(GritterMessage::TYPE_ALERT);

        $responseConfig = new ResponseConfigurator();
        $responseConfig->addResponseType($message);
        $responseConfig->addResponseType($shortMessage);

        $response = new Response();
        $response->add($responseConfig);

        return $this->getResponse()->setContent($response);
    }

    public function responseConfiguratorWithDerivingAction() {
        $exampleResponseConfig = new ExampleResponseConfigurator($this->getServiceLocator()->get('translator'));

        $response = new Response();
        $response->add($exampleResponseConfig);

        return $this->getResponse()->setContent($response);
    }

    public function sendTwoResponseTypesAction() {
        $message = new GenericMessage("#strt_id", "ACTION_TYPE_REPLACE", "innerHtml", "InnerHtml", array($this->getServiceLocator()->get('translator')->translate("<p style='margin: 0 0 0 0'>I am a new element and I was announced by a notification.</p>")));
        $shortMessage = $this->getServiceLocator()->get("MessageFactory")->shortMessage();
        $shortMessage->setTitle($this->getServiceLocator()->get('translator')->translate("A new element has appeared! (With a Response class only.)"));
        $shortMessage->setText($this->getServiceLocator()->get('translator')->translate("New Element"));
        $shortMessage->setType(GritterMessage::TYPE_ALERT);

        $response = new Response();
        $response->add($message);
        $response->add($shortMessage);

        return $this->getResponse()->setContent($response);
    }

    public function innerHtmlExampleOneAction() {
        $innerHtml = new InnerHtml("#inner_html_id1", $this->getServiceLocator()->get('translator')->translate("I am the first example to fill in content with the InnerHtml class."));

        $response = new Response();
        $response->add($innerHtml);

        return $this->getResponse()->setContent($response);
    }

    public function innerHtmlExampleTwoAction() {
        $innerHtml = new InnerHtml("#inner_html_id2", null, InnerHtml::ACTION_TYPE_REMOVE);

        $response = new Response();
        $response->add($innerHtml);

        return $this->getResponse()->setContent($response);
    }

    public function innerHtmlExampleThreeAction() {
        $innerHtml = new InnerHtml("#inner_html_id3", $this->getServiceLocator()->get('translator')->translate("I am a sibling and I will get another sibling."), InnerHtml::ACTION_TYPE_APPEND);

        $response = new Response();
        $response->add($innerHtml);

        return $this->getResponse()->setContent($response);
    }

    public function domManipulatorExampleOneAction() {
        $domManipulator = new DomManipulator("#dme_id1", "background-color", "#fff");

        $response = new Response();
        $response->add($domManipulator);

        return $this->getResponse()->setContent($response);
    }

    public function domManipulatorExampleTwoAction() {
        $domManipulator = new DomManipulator("#dme_id2", "highlight", null, DomManipulator::ACTION_TYPE_TOGGLE_CLASS);

        $response = new Response();
        $response->add($domManipulator);

        return $this->getResponse()->setContent($response);
    }

    public function consoleLogAction() {
        $consoleLog = new ConsoleLog(get_class($this));

        $response = new Response();
        $response->add($consoleLog);

        return $this->getResponse()->setContent($response);
    }

    public function appendTextAction() {
        $innerHtml = new InnerHtml("#tem_id", $this->getServiceLocator()->get('translator')->translate("Appended Text. "), InnerHtml::ACTION_TYPE_APPEND);

        $response = new Response();
        $response->add($innerHtml);

        return $this->getResponse()->setContent($response);
    }

    public function triggerFirstButtonAction() {
        $trigger = new TriggerEventManager("#tem_btn1");

        $response = new Response();
        $response->add($trigger);

        return $this->getResponse()->setContent($response);
    }

    // ----------------------------- WIZARD SECTION ----------------------------//
    public function wizardAction() {
        $response = new Response();
        $wizard = new \WasabiLib\Wizard\Wizard("#Wizard .modal-body", $this->getRequest(), $this->stepCollectionClosure(), $this->getServiceLocator());
        $wizard->disablePrevButton();
        if ($wizard->isFirstCall()) {
            $wizard->getStorageContainer()->clearStorage();
            $modal = new WasabiModal("Wizard", "Wizard Example", $wizard->getViewResult()->getViewModel());
            $modalView = new WasabiModalView("#wizard_modal", $this->getServiceLocator()->get("ViewRenderer"), $modal);
            $response->add($modalView);
        } else {
            $response->add($wizard->getViewResult());
        }

        return $this->getResponse()->setContent($response);
    }

    private function stepCollectionClosure() {
        $stepCollectionClosure = function () {
            $stepCollection = new StepCollection();
            $stepCollection->add($this->stepOne());
            $stepCollection->add($this->stepTwo());
            $stepCollection->add($this->stepThree());

            return $stepCollection;
        };
        return $stepCollectionClosure;
    }

    private function stepOne() {
        $viewModel = new ViewModel();
        $viewModel->setTemplate("wizard/wizard/stepone.phtml");
        $stepOne = new \WasabiLib\Wizard\StepController("Name", "Name");
        $stepOne->setFormAction("wizard");
        $stepOne->setViewModel($viewModel);

        $stepOne->setInitClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $this->form = new StepOneForm("stepOneForm", $closureArguments->getServiceLocator());
        });
        $stepOne->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $request = $closureArguments->getRequest();
            $serviceLocator = $closureArguments->getServiceLocator();
            if ($request->isPost()) {
                $formValidator = new StepOneFormValidator();
                $this->form->setInputFilter($formValidator->getInputFilter());
                $this->form->setData($request->getPost());
                if ($this->form->isValid()) {
                    $this->setStorageValue($this->form->getData());
                    return true;
                } else {
                    $this->setStorageValue($this->form->getData());
                    $response = new \WasabiLib\Ajax\Response();

                    $info = new Info("alert_info_one", "WARNING", "Please evaluate the required fields.");
                    $modal = new WasabiModalView("#wasabi_modal", $serviceLocator->get("ViewRenderer"), $info);
                    $response->add($modal);
                    $this->setProcessErrorMessage($response->asInjectedJS());

                    return false;
                }

            }
            return false;
        });


        return $stepOne;
    }

    private function stepTwo() {
        $viewModel = new ViewModel();
        $viewModel->setTemplate("wizard/wizard/steptwo.phtml");
        $stepTwo = new \WasabiLib\Wizard\StepController("Address", "Address");
        $stepTwo->setFormAction("wizard");
        $stepTwo->setViewModel($viewModel);

        $stepTwo->setInitClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $serviceLocator = $closureArguments->getServiceLocator();
            $this->form = new StepTwoForm("stepTwoForm", $serviceLocator);
        });
        $stepTwo->setProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $request = $closureArguments->getRequest();
            $serviceLocator = $closureArguments->getServiceLocator();
            if ($request->isPost()) {
                $formValidator = new StepTwoFormValidator();
                $this->form->setInputFilter($formValidator->getInputFilter());
                $this->form->setData($request->getPost());

                if ($this->form->isValid()) {
                    return true;
                } else {
                    $response = new \WasabiLib\Ajax\Response();

                    $info = new Info("alert_info_one", "WARNING", "Please evaluate the required fields.");
                    $modal = new WasabiModalView("#wasabi_modal", $serviceLocator->get("ViewRenderer"), $info);
                    $response->add($modal);
                    $this->setProcessErrorMessage($response->asInjectedJS());
                    return false;
                }
            }

        });

        $stepTwo->setPostProcessClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $this->setStorageValue($this->form->getData());
            return true;
        });
        $stepTwo->setLeaveToAncestorClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            $request = $closureArguments->getRequest();
            $this->setStorageValue($request->getPost());

            return true;
        });

        return $stepTwo;
    }

    private function stepThree() {
        $viewModel = new ViewModel();
        $viewModel->setTemplate("wizard/wizard/stepthree.phtml");
        $stepThree = new \WasabiLib\Wizard\StepController("Summary", "Summary");
        $stepThree->setFormAction("wizard");
        $stepThree->setViewModel($viewModel);

        $stepThree->setEnterClosure(function (\WasabiLib\Wizard\ClosureArguments $closureArguments) {
            /* @var $storageContainer StorageContainer */
            $storageContainer = $this->getStorageContainer();
            $storageIterator = $storageContainer->getIterator();
            $content = "<table  class='table  table-bordered table-hover table-striped'>";
            foreach ($storageIterator as $arrayElement) {
                foreach ($arrayElement as $key => $element) {
                    $content .= "<tr><td>".$key."</td><td>".$element."</td>";
                }

            }
            $content .= "</table>";
            $this->addViewModelVariablesAndContent("content", $content);

        });

        return $stepThree;
    }

    // ----------------------------- WIZARD SECTION ----------------------------//
    // ----------------------------- SIMPLE WIZARD SECTION ----------------------------//
    public function simpleWizardAction() {

        $response = new Response();
        $wizard = new \WasabiLib\Wizard\Wizard("#Wizard .modal-body", $this->getRequest(), $this->simpleStepCollectionClosure(), $this->getServiceLocator());
        $wizard->disablePrevButton();

        if ($wizard->isFirstCall()) {
            $wizard->getStorageContainer()->clearStorage();
            $modal = new WasabiModal("Wizard", "Wizard Example", $wizard->getViewResult()->getViewModel());
            $modalView = new WasabiModalView("#wizard_modal", $this->getServiceLocator()->get("ViewRenderer"), $modal);
            $response->add($modalView);
        } else {
            $response->add($wizard->getViewResult());
        }

        return $this->getResponse()->setContent($response);
    }

    private function simpleStepCollectionClosure() {
        $stepCollectionClosure = function () {
            $stepCollection = new StepCollection();
            $stepCollection->add($this->simpleStepOne());
            $stepCollection->add($this->simpleStepTwo());
            $stepCollection->add($this->simpleStepThree());

            return $stepCollection;
        };
        return $stepCollectionClosure;
    }

    private function simpleStepOne() {
        $stepOne = new \WasabiLib\Wizard\StepController("first step", "firstStep");
        $viewModel = new ViewModel();
        $viewModel->setTemplate("wizard/wizard/firstSimpleStep.phtml");
        $stepOne->setFormAction("simpleWizard");
        $stepOne->setViewModel($viewModel);

        return $stepOne;
    }

    private function simpleStepTwo() {
        $stepTwo = new \WasabiLib\Wizard\StepController("second step", "secondStep");
        $viewModel = new ViewModel();
        $viewModel->setTemplate("wizard/wizard/secondSimpleStep.phtml");
        $stepTwo->setFormAction("simpleWizard");
        $stepTwo->setViewModel($viewModel);

        return $stepTwo;

    }

    private function simpleStepThree() {
        $stepThree = new \WasabiLib\Wizard\StepController("finish", "finish");
        $viewModel = new ViewModel();
        $viewModel->setTemplate("wizard/wizard/thirdSimpleStep.phtml");
        $stepThree->setFormAction("simpleWizard");
        $stepThree->setViewModel($viewModel);

        return $stepThree;
    }
    // ----------------------------- SIMPLE WIZARD SECTION ----------------------------//
    // ----------------------------- EXAMPLES SECTION ----------------------------//
}
