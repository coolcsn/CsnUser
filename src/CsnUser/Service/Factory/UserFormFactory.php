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

namespace CsnUser\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use CsnUser\Entity\User;

class UserFormFactory implements FactoryInterface
{
  
    private $serviceLocator;
  
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Create admin user form
     *
     * Method to create the Doctrine ORM user form for edit/create users 
     *
     * @return Zend\Form\Form
     */
    public function createUserForm($userEntity, $entityManager)
    {
        $builder = new DoctrineAnnotationBuilder($entityManager);
        $form = $builder->createForm($userEntity);
        $form->setHydrator(new DoctrineHydrator($entityManager));
        $form->setAttribute('method', 'post');
        
        $form->add(array(
            'name' => 'passwordVerify',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'required' => true,
                'type'  => 'password',
            ),
        ));
        
        $form->add(array(
            'name' => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
            )
        ));
          
        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'type'  => 'submit',
            ),
        ));

        $form->getInputFilter()->add($form->getInputFilter()->getFactory()->createInput(array(
            'name' => 'passwordVerify',
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 6,
                        'max' => 20,
                    ),
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            )
        )));
        
        $userRepository = $entityManager->getRepository('CsnUser\Entity\User');
        $form->getInputFilter()->get('username')->getValidatorChain()->attach(
            new NoObjectExistsValidator(array(
                'object_repository' => $userRepository,
                'fields'            => array('username'),
                'messages' => array(
                    'objectFound' => $this->serviceLocator->get('MvcTranslator')->translate('This username is already taken'),
                ),
            ))
        );
        
        $form->getInputFilter()->get('email')->getValidatorChain()->attach(
            new NoObjectExistsValidator(array(
                'object_repository' => $userRepository,
                'fields'            => array('email'),
                'messages' => array(
                    'objectFound' => $this->serviceLocator->get('MvcTranslator')->translate('An user with this email already exists'),
                ),
            ))
        );
        
        $form->bind($userEntity);
    
        return $form;
    }
}
