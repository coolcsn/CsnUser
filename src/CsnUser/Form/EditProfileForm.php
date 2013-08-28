<?php
namespace CsnUser\Form;

use Zend\Form\Form;

class EditProfileForm extends Form
{
    public function __construct($name = null)
    {
		parent::__construct('registration');
        $this->setAttribute('method', 'post');
	
        $this->add(array(
            'name' => 'displayName',
            'attributes' => array(
                'type'  => 'text',
				'placeholder' =>'New display name',
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
                'class' => 'btn btn-success btn-lg',
            ),
        )); 
    }
}
