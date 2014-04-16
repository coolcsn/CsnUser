<?php
/**
 * coolcsn * Index Controller
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 */

namespace CsnUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CsnUser\Entity\User; // only for the filters
use CsnUser\Form\LoginForm;
use CsnUser\Form\LoginFilter;
use CsnUser\Options\ModuleOptions;

/**
 * <b>Authentication controller</b>
 * This controller has been build with educational purposes to demonstrate how authentication can be done
 */
class IndexController extends AbstractActionController
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Index action
     *
     * The method show to users they are guests
     *
     * @return Zend\View\Model\ViewModelarray navigation menu
     */
    public function indexAction()
    {
        return new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
    }

    /**
     * Log in action
     *
     * The method uses Doctrine Entity Manager to authenticate the input data
     *
     * @return Zend\View\Model\ViewModel|array login form|array messages|array navigation menu
     */
    public function loginAction()
    {
        if ($user = $this->identity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $form = new LoginForm();
        $form->get('submit')->setValue('Log in');
        $messages = null;

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter(new LoginFilter($this->getServiceLocator()));
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                $adapter = $authService->getAdapter();

                $usernameOrEmail = $data['usernameOrEmail'];
                
                try {
                    //  check for email first
                    if ($user = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $usernameOrEmail))) {
                        // Set username to the input array in place of the email
                        $data['usernameOrEmail'] = $user->getUsername();
                    }

                    $adapter->setIdentityValue($data['usernameOrEmail']);
                    $adapter->setCredentialValue($data['password']);

                    $authResult = $authService->authenticate();
                    if ($authResult->isValid()) {
                        $identity = $authResult->getIdentity();
                        $authService->getStorage()->write($identity);
                        $time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)

                        if ($data['rememberme']) {
                            $sessionManager = new \Zend\Session\SessionManager();
                            $sessionManager->rememberMe($time);
                        }

                        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
                    }
                } catch (\Exception $e) {
                    $viewModel = new ViewModel(array(
                        'navMenu' => $this->getOptions()->getNavMenu(),
                        'display_exceptions' => $this->getOptions()->getDisplayExceptions(),
                        'exception' => $e
                    ));
                    $viewModel->setTemplate('csn-user/index/login-error');
                    return $viewModel;
                }
       
                foreach ($authResult->getMessages() as $message) {
                    $messages .= "$message\n";
                }
            }
        }

        return new ViewModel(array(
                                'error' => 'Your authentication credentials are not valid',
                                'form'	=> $form,
                                'messages' => $messages,
                                'navMenu' => $this->getOptions()->getNavMenu()
                            ));
    }

    /**
     * Log out action
     *
     * The method destroys session for a logged user
     *
     * @return redirect to specific action
     */
    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

        // @todo Set up the auth adapter, $authAdapter

        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
        }
        $auth->clearIdentity();
        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();

        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());

    }

    /**
     * get entityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }

        return $this->em;
    }

     /**
     * set options
     *
     * @return IndexController
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('csnuser_module_options'));
        }

        return $this->options;
    }
}