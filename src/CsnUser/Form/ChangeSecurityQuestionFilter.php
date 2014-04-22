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

use Zend\InputFilter\InputFilter;
use CsnUser\Entity\Question;

class ChangeSecurityQuestionFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name'     => 'password',
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
        
        $this->add(array(
            'name'       => 'question',
            'required'   => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'Digits'
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'securityAnswer',
            'required'   => true,
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
                          'max'      => 100,
                      ),
                  ),
                  array (
                      'name' => 'Alnum',
                      'options' => array (
                          'allowWhiteSpace' => true,
                      ),
                  ),
              ),
        ));     
    }
}
