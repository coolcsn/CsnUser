<?php
/**
 * CsnUser
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */

namespace CsnUser\Form;

use Zend\Form\Form;

class ResetPasswordForm extends Form
{
    public function __construct($captchaNum = 3)
    {
        parent::__construct();
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'usernameOrEmail',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'true',
            ),
        ));
        
        $this->add(array(
            'name' => 'captcha',
            'type' => 'Zend\Form\Element\Captcha',
            'options' => array(
                'label' => ' ',
                'captcha' => new \Zend\Captcha\Figlet(array(
                    'wordLen' => $captchaNum,
                )),
            ),
        ));
                
        $this->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));
    }
}
