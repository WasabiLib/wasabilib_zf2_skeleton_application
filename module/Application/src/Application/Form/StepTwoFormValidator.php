<?php
/**
 * Created by PhpStorm.
 * User: nico.berndt
 * Date: 04.12.14
 * Time: 11:08
 */
namespace Application\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class StepTwoFormValidator implements InputFilterAwareInterface {
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();


            $inputFilter->add($factory->createInput([
                'name' => 'address',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(   array(
                    'name' =>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter your Address!'
                        ),
                    ),
                ),),
            ]));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}