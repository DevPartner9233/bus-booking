<?php

namespace Backend\Form\Booking;

use Booking\Entity\Booking;
use Square\Manager\SquareManager;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EditForm extends Form
{

    protected $squareManager;

    public function __construct(SquareManager $squareManager)
    {
        parent::__construct();

        $this->squareManager = $squareManager;
    }

    public function init()
    {
        $this->setName('bf');

        $this->add(array(
            'name' => 'bf-rid',
            'type' => 'Hidden',
            'attributes' => array(
                'id' => 'bf-rid',
            ),
        ));

        $this->add(array(
            'name' => 'bf-user',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'bf-user',
                'style' => 'width: 200px;',
            ),
            'options' => array(
                'label' => 'Gebucht auf',
            ),
        ));

        $squareOptions = array();

        foreach ($this->squareManager->getAll() as $sid => $square) {
            $squareOptions[$sid] = $square->get('name');
        }

        $this->add(array(
            'name' => 'bf-sid',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'bf-sid',
                'style' => 'width: 124px',
            ),
            'options' => array(
                'label' => 'Platz',
                'value_options' => $squareOptions,
            ),
        ));

        $this->add(array(
            'name' => 'bf-status-billing',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'bf-status-billing',
                'style' => 'width: 124px',
            ),
            'options' => array(
                'label' => 'Rechnungsstatus',
                'value_options' => Booking::$billingStatusOptions,
            ),
        ));

        $this->add(array(
            'name' => 'bf-quantity',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'bf-quantity',
                'style' => 'width: 110px;',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Anzahl Spieler',
            ),
        ));

        $this->add(array(
            'name' => 'bf-date-start',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'bf-date-start',
                'style' => 'width: 80px;',
            ),
            'options' => array(
                'label' => 'Datum (Start)',
            ),
        ));

        $this->add(array(
            'name' => 'bf-time-start',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'bf-time-start',
                'style' => 'width: 80px;',
            ),
            'options' => array(
                'label' => 'Uhrzeit (Start)',
            ),
        ));

        $this->add(array(
            'name' => 'bf-date-end',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'bf-date-end',
                'style' => 'width: 80px;',
            ),
            'options' => array(
                'label' => 'Datum (Ende)',
            ),
        ));

        $this->add(array(
            'name' => 'bf-time-end',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'bf-time-end',
                'style' => 'width: 80px;',
            ),
            'options' => array(
                'label' => 'Uhrzeit (Ende)',
            ),
        ));

        $this->add(array(
            'name' => 'bf-repeat',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'bf-repeat',
                'style' => 'width: 124px',
            ),
            'options' => array(
                'label' => 'Wiederholung',
                'value_options' => Booking::$repeatOptions,
            ),
        ));

        $this->add(array(
            'name' => 'bf-notes',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'bf-notes',
                'style' => 'width: 250px; height: 48px;',
            ),
            'options' => array(
                'label' => 'Notes',
                'notes' => 'These are only visible for administration',
            ),
        ));

        $this->add(array(
            'name' => 'bf-submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'bf-submit',
                'class' => 'default-button',
                'style' => 'width: 200px;',
            ),
        ));

        /* Input filters */

        $factory = new Factory();

        $this->setInputFilter($factory->createInputFilter(array(
            'bf-user' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier etwas ein',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                ),
            ),
            'bf-quantity' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier etwas ein',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier eine Zahl ein',
                        ),
                    ),
                ),
            ),
            'bf-date-start' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier etwas ein',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function($value) {
                                try {
                                    new \DateTime($value);

                                    return true;
                                } catch (\Exception $e) {
                                    return false;
                                }
                            },
                            'message' => 'Ungültiges Datum',
                        ),
                    ),
                ),
            ),
            'bf-time-start' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier etwas ein',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[0-9]?[0-9]:[0-9][0-9]$/',
                            'message' => 'Bitte geben Sie die Zeit im Format HH:MM ein',
                        ),
                    ),
                ),
            ),
            'bf-date-end' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier etwas ein',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function($value) {
                                    try {
                                        new \DateTime($value);

                                        return true;
                                    } catch (\Exception $e) {
                                        return false;
                                    }
                                },
                            'message' => 'Ungültiges Datum',
                        ),
                    ),
                ),
            ),
            'bf-time-end' => array(
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'Bitte geben Sie hier etwas ein',
                        ),
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[0-9]?[0-9]:[0-9][0-9]$/',
                            'message' => 'Bitte geben Sie die Zeit im Format HH:MM ein',
                        ),
                    ),
                ),
            ),
            'bf-notes' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StringTrim'),
                ),
            ),
        )));
    }

}