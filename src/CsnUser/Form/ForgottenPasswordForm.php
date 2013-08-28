<?php
namespace CsnUser\Form;

use Zend\Form\Form;

class ForgottenPasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
		
        $this->add(array(
            'name' => 'usernameOrEmail',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Username or e-mail',
            ),
            'options' => array(
                'label' => ' ',
            ),
        ));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
