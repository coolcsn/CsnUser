<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 * 
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

use CsnUser\Entity\User;
use CsnUser\Options\ModuleOptions;
use CsnUser\Service\UserService as UserCredentialsService;

/**
 * Admn controller
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
        if(!$this->identity()) {
          return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
      
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
    public function createUserAction()
    {
        if(!$this->identity()) {
          return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
      
        try {
            $user = new User;
            
            $form = $this->getUserFormHelper()->createUserForm($user, 'CreateUser');
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setValidationGroup('username', 'email', 'firstName', 'lastName', 'password', 'passwordVerify', 'language', 'state', 'role', 'question', 'answer', 'csrf');
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $entityManager = $this->getEntityManager();
                    $user->setEmailConfirmed(false);
                    $user->setRegistrationDate(new \DateTime());
                    $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                    $user->setPassword(UserCredentialsService::encryptPassword($user->getPassword()));
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('User created Successfully'));
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
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTemplate('csn-user/admin/new-user-form');
        return $viewModel;
    }

    /**
     * Edit action
     *
     * Method to update an user
     *
     * @return Zend\View\Model\ViewModel
     */
    public function editUserAction()
    {
        if(!$this->identity()) {
          return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
      
        try {
            $id = (int) $this->params()->fromRoute('id', 0);
    
            if ($id == 0) {
                $this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('User ID invalid'));
                return $this->redirect()->toRoute('user-admin');
            }
            
            $entityManager = $this->getEntityManager();
            $user = $entityManager->getRepository('CsnUser\Entity\User')->find($id);
            
            $form = $this->getUserFormHelper()->createUserForm($user, 'EditUser');
            
            $form->setAttributes(array(
                'action' => $this->url()->fromRoute('user-admin', array('action' => 'edit-user', 'id' => $id)),
            ));
              	
            $request = $this->getRequest();
            if ($request->isPost()) {
                $form->setValidationGroup('username', 'email', 'firstName', 'lastName', 'language', 'state', 'role', 'question', 'answer', 'csrf');
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('User Updated Successfully'));
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
        $viewModel->setTemplate('csn-user/admin/edit-user-form');
        return $viewModel;
    }

    /**
     * Delete action
     *
     * Method to delete an user from his ID
     *
     * @return Zend\View\Model\ViewModel
     */
    public function deleteUserAction()
    {
        if(!$this->identity()) {
          return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
      
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id == 0) {
            $this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('User ID invalid'));
            return $this->redirect()->toRoute('user-admin');
        }
           
        try {
            $entityManager = $this->getEntityManager();
            $user = $entityManager->getRepository('CsnUser\Entity\User')->find($id);
            $entityManager->remove($user);
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('User Deleted Successfully'));
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
     * Disable action
     *
     * Method to disable an user from his ID
     *
     * @return Zend\View\Model\ViewModel
     */
    public function setUserStateAction()
    {
        if(!$this->identity()) {
          return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
      
        $id = (int) $this->params()->fromRoute('id', 0);
        $state = (int) $this->params()->fromRoute('state', -1);
        
        if ($id === 0 || $state === -1) {
            $this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('User ID or state invalid'));
            return $this->redirect()->toRoute('user-admin');
        }
         
        try {
            $entityManager = $this->getEntityManager();
            $user = $entityManager->getRepository('CsnUser\Entity\User')->find($id);
            $user->setState($entityManager->find('CsnUser\Entity\State', $state));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('User Updated Successfully'));
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