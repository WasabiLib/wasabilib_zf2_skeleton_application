<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use WasabiLib\Form\FormExtended;
class StepOneForm extends FormExtended {
    public function __construct($name = null, $serviceLocator) {
        parent::__construct($name, $serviceLocator);


        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'firstname',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'firstname',
                'placeholder' => 'First Name',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'First Name',
            ),
        ));

        $this->add(array(
            'name' => 'lastname',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'lastname',
                'placeholder' => 'Last Name',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Last Name',
            ),
        ));

    }
}