<?php
namespace CsnUser\Form;

use Zend\Form\Form;

class RegistrationForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
				'placeholder' =>'Username',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
		
		$this->add(array(
            'name' => 'displayName',
            'attributes' => array(
                'type'  => 'text',
				'placeholder' =>'Display Name',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
		
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
				'placeholder' =>'Email',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));	
		
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
				'placeholder' =>'Password',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
		
        $this->add(array(
            'name' => 'passwordConfirm',
            'attributes' => array(
                'type'  => 'password',
				'placeholder' =>'Confirm Password',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));	

		$this->add(array(
			'type' => 'Zend\Form\Element\Captcha',
			'name' => 'captcha',
			'attributes' => array(
				'placeholder' =>'Please verify you are human',
            ),
			'options' => array(
				'label' => ' ',
				'captcha' => new \Zend\Captcha\Figlet(array(
					'wordLen' => 3,
				)),
			),
		));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
