<?php
namespace CsnUser\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ChangeEmailFilter extends InputFilter
{
	public function __construct($sm)
	{

		$this->add(array(
			'name'     => 'currentPassword',
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 6,
						'max'      => 12,
					),
				),
			),
		));	
		
		$this->add(array(
            'name'       => 'newEmail',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
				array(
					'name'		=> 'DoctrineModule\Validator\NoObjectExists',
					'options' => array(
						'object_repository' => $sm->get('doctrine.entitymanager.orm_default')->getRepository('CsnUser\Entity\User'),
						'fields'            => 'email'
					),
				),
            ),
        ));
		

		$this->add(array(
			'name'     => 'newEmailConfirm',
			'required' => true,
			'validators' => array(
				array(
					'name' => 'EmailAddress'
                ),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'newEmail',
                    ),
                ),
			),
		));
	}
}