<?php
namespace CsnUser\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class EditProfileFilter extends InputFilter
{
	public function __construct($sm)
	{
		$this->add(array(
			'name'     => 'displayName',
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
						'max'      => 20,
					),
				),
			),
		));
	}
}