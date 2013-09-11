<?php
namespace CsnUser\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        /*
        $this->add(array(
            'name' => 'usr_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        */
        $this->add(array(
            'name' => 'usernameOrEmail',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' =>'Username or email',
            ),
            'options' => array(
                'label' => ' ',
                //'label' => 'Username',
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
                //'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'rememberme',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Remember me?',
                'label_attributes' => array(
                    'class'  => 'checkbox'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Log in',
                'class' => 'btn btn-success btn-lg',
            ),
        ));
    }
}
