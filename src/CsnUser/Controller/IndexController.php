<?php
namespace CsnUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Adapter\Exception;
use Zend\Authentication\Result as AuthenticationResult;

use CsnUser\Entity\User; // only for the filters
use CsnUser\Form\LoginForm;
use CsnUser\Form\LoginFilter;

use CsnUser\Form\ChangeEmailForm;
use CsnUser\Form\ChangeEmailFilter;

use CsnUser\Options\ModuleOptions;

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
    
    public function indexAction()
    {
	
        return new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
    }
	
	
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
				$login = $request->getPost('login');
				$usernameOrEmail = $data['usernameOrEmail'];

				if($user = $this->getEntityManager()->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $usernameOrEmail)))
				{
					$data['usernameOrEmail'] = $user->getUsername(); // Set username to the input array in place of the email
				}
				
				$adapter->setIdentityValue($data['usernameOrEmail']);
				$adapter->setCredentialValue($data['password']);
				$authResult = $authService->authenticate();
				if ($authResult->isValid()) {
					$identity = $authResult->getIdentity();
					$authService->getStorage()->write($identity);
					$time = 1209600; // 14 days = 1209600/3600 = 336 hours => 336/24

					if ($data['rememberme']) {
						$sessionManager = new \Zend\Session\SessionManager();
						$sessionManager->rememberMe($time);
					}
                    return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
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
