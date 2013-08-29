<?php
namespace CsnUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;

use CsnUser\Entity\User;
// Doctrine Annotations
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use CsnUser\Form\RegistrationForm;
use CsnUser\Form\RegistrationFilter;
use CsnUser\Form\ForgottenPasswordForm;
use CsnUser\Form\ForgottenPasswordFilter;

use CsnUser\Form\ForgottennPasswordForm;
use CsnUser\Form\ForgottennPasswordFilter;

use CsnUser\Form\ChangeEmailForm;
use CsnUser\Form\ChangeEmailFilter;

use CsnUser\Form\ChangePasswordForm;
use CsnUser\Form\ChangePasswordFilter;

use CsnUser\Form\EditProfileForm;
use CsnUser\Form\EditProfileFilter;

use CsnUser\Options\ModuleOptions;

class RegistrationController extends AbstractActionController
{
     /**
     * @var ModuleOptions
     */
    protected $options;
    
    public function indexAction()
    {
		
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
			if (!$user = $this->identity()) {
				$user = new User;
				//1)  A lot of work to manualy change the form add fields etc. Better use a form class
	//-		$form = $this->getRegistrationForm($entityManager, $user);

				// 2) Better use a form class
				$form = new RegistrationForm();
				$form->get('submit')->setValue('Sign up');
				$form->setHydrator(new DoctrineHydrator($entityManager,'CsnUser\Entity\User'));		

				$form->bind($user);		
				$request = $this->getRequest();
				if ($request->isPost()) {
						$form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
						$form->setData($request->getPost());
						 if ($form->isValid()) {
								$this->prepareData($user);
								
								$this->flashMessenger()->addMessage($user->getEmail());
								$entityManager->persist($user);
								$entityManager->flush();			
								$this->sendConfirmationEmail($user);	
								return $this->redirect()->toRoute('registration-success');					
						}			 
				}
				return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu()));
		}
		else
		{
			return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
		}
    }
	public function changeEmailAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		if ($user = $this->identity()) {
			$form = new ChangeEmailForm();
            $form->get('submit')->setValue('Change email');
			$request = $this->getRequest();
			$message = null;
			if ($request->isPost()) {
				$form->setInputFilter(new ChangeEmailFilter($this->getServiceLocator()));
				$form->setData($request->getPost());
				if($form->isValid()) {
					$data = $form->getData();
					$currentPassword = $data['currentPassword'];
					$newMail = $data['newEmail'];
					$originalPassword = $user->getPassword();
					$comparePassword = $this->encryptPassword($this->getOptions()->getStaticSalt(), $currentPassword, $user->getPasswordSalt());
					
					if($originalPassword == $comparePassword )
					{
						$email = $user->setEmail($newMail);
						$message = 'Your email has been changed to '. $newMail.'.';
						
						 $entityManager->persist($user);
                            $entityManager->flush();
					}
					else
					{
						$message = 'Your current password is not correct.';
					}
				}
			}
			
			return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message));
		}
		else
		{
			return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
		}
	}
	public function changePasswordAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		if ($user = $this->identity()) {
			$form = new ChangePasswordForm();
            $form->get('submit')->setValue('Change password');
			$request = $this->getRequest();
			$message = null;
			if ($request->isPost()) {
				$form->setInputFilter(new ChangePasswordFilter($this->getServiceLocator()));
				$form->setData($request->getPost());
				if($form->isValid()) {
					$data = $form->getData();
					$currentPassword = $data['currentPassword'];
					$newPassword = $data['newPassword'];
					$originalPassword = $user->getPassword();
					$comparePassword = $this->encryptPassword($this->getOptions()->getStaticSalt(), $currentPassword, $user->getPasswordSalt());
					
					if($originalPassword == $comparePassword )
					{
						$password = $this->encryptPassword($this->getOptions()->getStaticSalt(), $newPassword, $user->getPasswordSalt());
						$email = $user->setPassword($password);
						$entityManager->persist($user);
                            $entityManager->flush();
						$message = 'Your password has been changed successfully.';
					}
					else
					{
						$message = 'Your current password is not correct.';
					}
				}
			}
			
			return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message));
		}
		else
		{
			return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
		}
	}
	
	public function editProfileAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		if ($user = $this->identity()) {
			$form = new EditProfileForm();
            $form->get('submit')->setValue('Save changes');
			$email = $user->getEmail();
			$username = $user->getUsername();
			$displayname = $user->getDisplayName();
			$request = $this->getRequest();
			$message = null;
			if ($request->isPost()) {
				$form->setInputFilter(new EditProfileFilter($this->getServiceLocator()));
				$form->setData($request->getPost());
				if($form->isValid()) {
					$data = $form->getData();
					$currentDisplayname = $user->getDisplayName();
					$newDisplayname = $data['displayName'];
					if($currentDisplayname != $newDisplayname)
					{
						$newnewdisplayname = $user->setDisplayName($newDisplayname);
						$entityManager->persist($user);
                            $entityManager->flush();
						$message = 'Your display name has been changed to: '. $newDisplayname.'.';
					}
				}
			}
			return new ViewModel(array('form' => $form, 'email' => $email, 'username' => $username, 'message' => $message,
			'displayname' => $displayname, 'navMenu' => $this->getOptions()->getNavMenu()));
		}
		else
		{
			return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
		}
	}

    public function registrationSuccessAction()
    {
            $email = null;
            $flashMessenger = $this->flashMessenger();
            if ($flashMessenger->hasMessages()) {
                    foreach($flashMessenger->getMessages() as $key => $value) {
                            $email .=  $value;
                    }
            }
            if($email != null){
            	return new ViewModel(array('email' => $email, 'navMenu' => $this->getOptions()->getNavMenu()));
            }else{
            	return $this->redirect()->toRoute('login');
            }
    }

    public function confirmEmailAction()
    {
            $token = $this->params()->fromRoute('id');
            $viewModel = new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
            try {
                    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                    if($token !== '' && $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('registrationToken' => $token))){
                    	$user->setRegistrationToken(md5(uniqid(mt_rand(), true))); // change immediately taken to prevent multiple requests to db
		                $user->setState(1);
		                $user->setEmailConfirmed(1);
		                $entityManager->persist($user);
		                $entityManager->flush();
                    }else{
                    	return $this->redirect()->toRoute('login');
                    }
            }
            catch(\Exception $e) {
                    $viewModel->setTemplate('csn-user/registration/confirm-email-error');
            }
            return $viewModel;
    }

    public function confirmEmailChangePasswordAction()
    {
            $token = $this->params()->fromRoute('id');
            $viewModel = new ViewModel(array('navMenu' => $this->getOptions()->getNavMenu()));
            try {
                    $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                    if($token !== '' && $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('registrationToken' => $token))){
						$user->setRegistrationToken(md5(uniqid(mt_rand(), true))); // change immediately taken to prevent multiple changing of password
		                $password = $this->generatePassword();
		                $passwordHash = $this->encryptPassword($this->getOptions()->getStaticSalt(), $password, $user->getPasswordSalt());
		                $user->setPassword($passwordHash);
		                $email = $user->getEmail();
		                $username = $user->getUsername();
		                $this->sendPasswordByEmail($username, $email, $password);
		                $this->flashMessenger()->addMessage($email);
		                $entityManager->persist($user);
		                $entityManager->flush();
		                $viewModel = new ViewModel(array('email' => $email, 'navMenu' => $this->getOptions()->getNavMenu()));
                    }else{
                    	return $this->redirect()->toRoute('user');
                    }
            }
            catch(\Exception $e) {
                    $viewModel->setTemplate('csn-user/registration/confirm-email-change-password-error', array('navMenu' => $this->getOptions()->getNavMenu()));
            }
            return $viewModel;
    }
/*
* without Confirmation email; Only send a new password;
    public function forgottenPasswordAction()
    {
            $form = new ForgottenPasswordForm();
            $form->get('submit')->setValue('Send');
            $request = $this->getRequest();
            if ($request->isPost()) {
                    $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
                    $form->setData($request->getPost());
                     if ($form->isValid()) {
                            $data = $form->getData();
                            $email = $data['email'];
                            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                            $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $email));
                            $password = $this->generatePassword();
                            $passwordHash = $this->encryptPassword($this->getOptions()->getStaticSalt(), $password, $user->getPasswordSalt());
                            $this->sendPasswordByEmail($email, $password);
                            $this->flashMessenger()->addMessage($email);
                            $user->setPassword($passwordHash);
                            $entityManager->persist($user);
                            $entityManager->flush();				
            return $this->redirect()->toRoute('default', array('controller'=>'registration', 'action'=>'password-change-success'));
                    }					
            }		
            return new ViewModel(array('form' => $form));			
    }
* 
*/

    /*public function forgottenPasswordAction()
    {
            $form = new ForgottenPasswordForm();
            $form->get('submit')->setValue('Send');
            $request = $this->getRequest();
            if ($request->isPost()) {
                    $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
                    $form->setData($request->getPost());
                     if ($form->isValid()) {
                         $data = $form->getData();
                         $email = $data['email'];
                         $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                         $user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $email));
                         $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                         $this->sendConfirmationEmailChangePassword($user);
                            $this->flashMessenger()->addMessage($user->getEmail());
                            $entityManager->persist($user);
                            $entityManager->flush();
                            return $this->redirect()->toRoute('password-change-success');
                    }	

            }		
            return new ViewModel(array('form' => $form));			
    }/*/
	
	public function forgottenPasswordAction()
    {
            $form = new ForgottenPasswordForm();
            $form->get('submit')->setValue('Send reset email');
            $request = $this->getRequest();
            $message = null;
            if ($request->isPost()) {
                    $form->setInputFilter(new ForgottenPasswordFilter($this->getServiceLocator()));
					$form->setData($request->getPost());
                    if ($form->isValid()) {
                        $data = $form->getData();
                        $usernameOrEmail = $data['usernameOrEmail'];
                        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
						if($user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $usernameOrEmail)))
						{
							$user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('email' => $usernameOrEmail));
							$user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
							$this->sendConfirmationEmailChangePassword($user);
							$this->flashMessenger()->addMessage($user->getEmail());
							$entityManager->persist($user);
							$entityManager->flush();
							return $this->redirect()->toRoute('password-change-success');
						}
						else if($user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('username' => $usernameOrEmail)))
						{
							$user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('username' => $usernameOrEmail));
							$user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
							$this->sendConfirmationEmailChangePassword($user);
							$this->flashMessenger()->addMessage($user->getEmail());
							$entityManager->persist($user);
							$entityManager->flush();
							return $this->redirect()->toRoute('password-change-success');
						}
						else
						{
							$message = 'The username or email is not valid!';
						}
                   }
            }
            return new ViewModel(array('form' => $form, 'navMenu' => $this->getOptions()->getNavMenu(), 'message' => $message));			
    }

    public function passwordChangeSuccessAction()
    {
            $email = null;
            $flashMessenger = $this->flashMessenger();
            if ($flashMessenger->hasMessages()) {
                    foreach($flashMessenger->getMessages() as $key => $value) {
                            $email .=  $value;
                    }
            }
            if($email != null){
            	return new ViewModel(array('email' => $email, 'navMenu' => $this->getOptions()->getNavMenu()));
            }else{
            	return $this->redirect()->toRoute('user');
            }
    }	

    public function prepareData($user)
    {
            $user->setState(0);
            $user->setPasswordSalt($this->generateDynamicSalt());				
            $user->setPassword($this->encryptPassword(
                                                            $this->getOptions()->getStaticSalt(), 
                                                            $user->getPassword(), 
                                                            $user->getPasswordSalt()
            ));
            $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
            $role = $entityManager->find('CsnUser\Entity\Role', 2);
            $user->setRole($role);
            $language = $entityManager->find('CsnUser\Entity\Language', 1);
            $user->setLanguage($language);
            $user->setRegistrationDate(new \DateTime());
            $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
            $user->setEmailConfirmed(0);

            return $user;
    }

    public function generateDynamicSalt()
    {
                $dynamicSalt = '';
                for ($i = 0; $i < 50; $i++) {
                        $dynamicSalt .= chr(rand(33, 126));
                }
        return $dynamicSalt;
    }

    public function encryptPassword($staticSalt, $password, $dynamicSalt)
    {
		return $password = md5($staticSalt . $password . $dynamicSalt);
    }
    
    public function generatePassword($l = 8, $c = 0, $n = 0, $s = 0) {
             // get count of all required minimum special chars
             $count = $c + $n + $s;
             $out = '';
             // sanitize inputs; should be self-explanatory
             if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
                      trigger_error('Argument(s) not an integer', E_USER_WARNING);
                      return false;
             }
             elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
                      trigger_error('Argument(s) out of range', E_USER_WARNING);
                      return false;
             }
             elseif($c > $l) {
                      trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
                      return false;
             }
             elseif($n > $l) {
                      trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
                      return false;
             }
             elseif($s > $l) {
                      trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
                      return false;
             }
             elseif($count > $l) {
                      trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
                      return false;
             }

             // all inputs clean, proceed to build password

             // change these strings if you want to include or exclude possible password characters
             $chars = "abcdefghijklmnopqrstuvwxyz";
             $caps = strtoupper($chars);
             $nums = "0123456789";
             $syms = "!@#$%^&*()-+?";

             // build the base password of all lower-case letters
             for($i = 0; $i < $l; $i++) {
                      $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
             }

             // create arrays if special character(s) required
             if($count) {
                      // split base password to array; create special chars array
                      $tmp1 = str_split($out);
                      $tmp2 = array();

                      // add required special character(s) to second array
                      for($i = 0; $i < $c; $i++) {
                               array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
                      }
                      for($i = 0; $i < $n; $i++) {
                               array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
                      }
                      for($i = 0; $i < $s; $i++) {
                               array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
                      }

                      // hack off a chunk of the base password array that's as big as the special chars array
                      $tmp1 = array_slice($tmp1, 0, $l - $count);
                      // merge special character(s) array with base password array
                      $tmp1 = array_merge($tmp1, $tmp2);
                      // mix the characters up
                      shuffle($tmp1);
                      // convert to string for output
                      $out = implode('', $tmp1);
             }

             return $out;
    }

    public function sendConfirmationEmail($user)
    {
            $hostname    = $_SERVER['HTTP_HOST'];
            $fullLink = "http://" . $hostname . $this->url()->fromRoute('confirm-email/default', array(
                                            'controller' => 'registration', 
                                            'action' => 'confirm-email', 
                                            'id' => $user->getRegistrationToken()));
            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();
            $this->getRequest()->getServer();  //Server vars
            $message->addTo($user->getEmail())
                            ->addFrom('praktiki@coolcsn.com')
                            ->setSubject('Please, confirm your registration!')
                            ->setBody("Please, click the link to confirm your registration => " . $fullLink );
            $transport->send($message);
    }

    public function sendConfirmationEmailChangePassword($user)
    {
            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();

            $hostname    = $_SERVER['HTTP_HOST'];
            $fullLink = "http://" . $hostname . $this->url()->fromRoute('confirm-email-change-password/default', array(
                                            'controller' => 'registration', 
                                            'action' => 'confirm-email-change-password', 
                                            'id' => $user->getRegistrationToken()));

            $this->getRequest()->getServer(); 
            $message->addTo($user->getEmail())
                            ->addFrom('praktiki@coolcsn.com')
                            ->setSubject('Please, confirm your request to change password!')
                            ->setBody('Hi, '.$user->getUsername().". Please, follow ". $fullLink . " to confirm your request to change password.");
            $transport->send($message);
    }

    public function sendPasswordByEmail($username, $email, $password)
    {
    $hostname    = $_SERVER['HTTP_HOST'];
    $fullLink = "http://" . $hostname;

            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();
            $this->getRequest()->getServer();  //Server vars
            $message->addTo($email)
                            ->addFrom('praktiki@coolcsn.com')
                            ->setSubject('Your password has been changed!')
                            ->setBody('Hello again '.$username.'. Your new password is: ' .
                                    $password . '. Please, follow ' . $fullLink . '/login/ to log in with your new password.'
                            );
            $transport->send($message);		
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
