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

namespace CsnUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

//@TODO Check why this AnnotationBuilder causes exception Zend\I18n\Validator component requires the intl PHP extension
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use CsnUser\Entity\User;
use CsnUser\Options\ModuleOptions;

/**
 * <b>Admin controller</b>
 * This controller has been build with educational purposes to demonstrate how administration can be done
 */

class AdminController extends AbstractActionController
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    /**
     * @var Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;
    
    /**
     * @var Zend\Form\Form
     */
    protected $userFormHelper;
    
    /**
     * Index action
     *
     * Method to show an user list
     *
     * @return Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $users = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findall();
        return new ViewModel(array('users' => $users));
    }    
    
    /**
     * Create action
     *
     * Method to create an user
     *
     * @return Zend\View\Model\ViewModel
     */
    public function createAction()
    {
        try {
            $entityManager = $this->getEntityManager();
            $user = new User;
            
            $form = $this->getUserFormHelper()->createUserForm($user, $entityManager );

            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    //@TODO Implement flash messages!
                    return $this->redirect()->toRoute('user-admin');                                        
                }
            }        
        }
        catch (\Exception $e) {
            return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                $this->getTranslatorHelper()->translate('Something went wrong during user creation! Please, try again later.'),
                $e,
                $this->getOptions()->getDisplayExceptions(),
                false
            );
        }
        
        $viewModel = new ViewModel(array(
            'form' => $form,
            'headerLabel' => $this->getTranslatorHelper()->translate('Create User')
        ));
        $viewModel->setTemplate('csn-user/admin/userForm');
        return $viewModel;
    }

    /**
     * Update action
     *
     * Method to update an user
     *
     * @return Zend\View\Model\ViewModel
     */
    public function updateAction()
    {
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
    
            //@TODO Implement flash messages!
            if ($id == 0)
                return $this->redirect()->toRoute('user-admin');
            
            $entityManager = $this->getEntityManager();
            $user = $entityManager->getRepository('CsnUser\Entity\User')->find($id);
            
            $form = $this->getUserFormHelper()->createUserForm($user, $entityManager);
              	
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    //@TODO Implement flash messages!
                    return $this->redirect()->toRoute('user-admin');
                }
            }  
        }      
        catch (\Exception $e) {
            return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                $this->getTranslatorHelper()->translate('Something went wrong during update user process! Please, try again later.'),
                $e,
                $this->getOptions()->getDisplayExceptions(),
                false
            );
        }
        
        $viewModel = new ViewModel(array(
            'form' => $form,
            'headerLabel' => $this->getTranslatorHelper()->translate('Edit User').' - '.$user->getDisplayName(),
        ));
        $viewModel->setTemplate('csn-user/admin/userForm');
        return $viewModel;
    }

    /**
     * Delete action
     *
     * Method to delete an user from his ID
     *
     * @return Zend\View\Model\ViewModel
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        //@TODO Implement flash messages!
        if ($id == 0)
            return $this->redirect()->toRoute('user-admin');
           
        try {
            $entityManager = $this->getEntityManager();
            $user = $entityManager->getRepository('CsnUser\Entity\User')->find($id);
            $entityManager->remove($user);
            $entityManager->flush();
        }
        catch (\Exception $e) {
            return $this->getServiceLocator()->get('csnuser_error_view')->createErrorView(
                $this->getTranslatorHelper()->translate('Something went wrong during user delete process! Please, try again later.'),
                $e,
                $this->getOptions()->getDisplayExceptions(),
                false
            );
        }

        return $this->redirect()->toRoute('user-admin');
    }
    
    /**
     * Create error view
     *
     * Method to create error view to display possible exceptions
     *
     * @return Zend\Form\Form
     */
    private function createErrorView($errorMessage, $exception, $displayExceptions = false, $displayNavMenu = false )
    {
      $viewModel = new ViewModel(array(
          'navMenu' => $displayNavMenu,
          'display_exceptions' => $displayExceptions,
          'errorMessage' => $errorMessage,
          'exception' => $exception,
      ));
      $viewModel->setTemplate('csn-user/error/error');
      return $viewModel;
    }  
    
    /**
     * get options
     *
     * @return ModuleOptions
     */
    private function getOptions()
    {
      if (null === $this->options) {
        $this->options = $this->getServiceLocator()->get('csnuser_module_options');
      }
          
      return $this->options;
    }

    /**
     * get entityManager
     *
     * @return EntityManager
     */
    private function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }

        return $this->entityManager;
    }
    
    /**
     * get translatorHelper
     *
     * @return  Zend\Mvc\I18n\Translator
     */
    private function getTranslatorHelper()
    {
      if (null === $this->translatorHelper) {
        $this->translatorHelper = $this->getServiceLocator()->get('MvcTranslator');
      }
    
      return $this->translatorHelper;
    }
    
    /**
     * get userFormHelper
     *
     * @return  Zend\Form\Form
     */
    private function getUserFormHelper()
    {
      if (null === $this->userFormHelper) {
        $this->userFormHelper = $this->getServiceLocator()->get('csnuser_user_form');
      }
    
      return $this->userFormHelper;
    }
}
