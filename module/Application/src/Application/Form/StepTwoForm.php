<?php
/**
 * Created by PhpStorm.
 * User: nico.berndt
 * Date: 04.12.14
 * Time: 11:07
 */
namespace Application\Form;

use Zend\Captcha;
use Zend\Form\Element;
use Zend\Form\Form;
use WasabiLib\Form\FormExtended;

class StepTwoForm extends  FormExtended {
    public function __construct($name = null, $serviceLocator) {
        parent::__construct($name,$serviceLocator);

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'address',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'address',
                'placeholder' => 'Address',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Address',
            ),
        ));

    }
}