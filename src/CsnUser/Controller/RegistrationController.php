<?php
namespace CsnUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use CsnUser\Entity\User;

// a test class in a coolcsn namespace for installer. You can remove the next line
//use CsnBase\Zend\Validator\ConfirmPassword;

// Doctrine Annotations
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineORMModule\Form\Annotation\AnnotationBuilder as DoctrineAnnotationBuilder;

// Zend Annotation 
use Zend\Form\Annotation\AnnotationBuilder;
// for the form
use Zend\Form\Element;

use CsnUser\Form\RegistrationForm;
use CsnUser\Form\RegistrationFilter;
use CsnUser\Form\ForgottenPasswordForm;
use CsnUser\Form\ForgottenPasswordFilter;

use Zend\Mail\Message;

class RegistrationController extends AbstractActionController
{

	public function indexAction()
	{
		$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$user = new User;
		//1)  A lot of work to manualy change the form add fields etc. Better use a form class
//-		$form = $this->getRegistrationForm($entityManager, $user);

		// 2) Better use a form class
		$form = new RegistrationForm();
		$form->get('submit')->setValue('Register');
		$form->setHydrator(new DoctrineHydrator($entityManager,'CsnUser\Entity\User'));		

		$form->bind($user);		
		$request = $this->getRequest();
                if ($request->isPost()) {
			$form->setInputFilter(new RegistrationFilter($this->getServiceLocator()));
			$form->setData($request->getPost());
			 if ($form->isValid()) {
				$this->prepareData($user);
				$this->sendConfirmationEmail($user);
				$this->flashMessenger()->addMessage($user->getEmail());
				$entityManager->persist($user);
				$entityManager->flush();				
				return $this->redirect()->toRoute('csn-user/default', array('controller'=>'registration', 'action'=>'registration-success'));					
			}			 
		}
		return new ViewModel(array('form' => $form));
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
		return new ViewModel(array('email' => $email));
	}

	public function confirmEmailAction()
	{
		$token = $this->params()->fromRoute('id');
		$viewModel = new ViewModel(array('registrationToken' => $token));
		//$viewModel = new ViewModel(array('token' => $token)); //original
		try {
			$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
			$user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('registrationToken' => $token)); // 
			
			$user->setState(1);
			$user->setEmailConfirmed(1);
			$entityManager->persist($user);
			$entityManager->flush();
		}
		catch(\Exception $e) {
			$viewModel->setTemplate('csn-user/registration/confirm-email-error.phtml');
		}
		return $viewModel;
	}
        
        public function confirmEmailChangePasswordAction()
	{
		$token = $this->params()->fromRoute('id');
		$viewModel = new ViewModel(array('registrationToken' => $token));
		//$viewModel = new ViewModel(array('token' => $token)); //original
		try {
			$entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
			$user = $entityManager->getRepository('CsnUser\Entity\User')->findOneBy(array('registrationToken' => $token)); 
			
                        $password = $this->generatePassword();
                        $passwordHash = $this->encryptPassword($this->getStaticSalt(), $password, $user->getPasswordSalt());
                        $user->setPassword($passwordHash);
                        $email = $user->getEmail();
						$username = $user->getUsername();
                        $this->sendPasswordByEmail($username, $email, $password);
                        $this->flashMessenger()->addMessage($email);
			$entityManager->persist($user);
			$entityManager->flush();
		}
		catch(\Exception $e) {
			$viewModel->setTemplate('csn-user/registration/confirm-email-change-password-error.phtml');
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
				$passwordHash = $this->encryptPassword($this->getStaticSalt(), $password, $user->getPasswordSalt());
				$this->sendPasswordByEmail($email, $password);
				$this->flashMessenger()->addMessage($email);
				$user->setPassword($passwordHash);
				$entityManager->persist($user);
				$entityManager->flush();				
                return $this->redirect()->toRoute('csn-user/default', array('controller'=>'registration', 'action'=>'password-change-success'));
			}					
		}		
		return new ViewModel(array('form' => $form));			
	}
 * 
 */
        
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
                             $user->setRegistrationToken(md5(uniqid(mt_rand(), true)));
                             $this->sendConfirmationEmailChangePassword($user);
				$this->flashMessenger()->addMessage($user->getEmail());
				$entityManager->persist($user);
				$entityManager->flush();
                                return $this->redirect()->toRoute('csn-user/default', array('controller'=>'registration', 'action'=>'password-change-success'));
			}	
                        
		}		
		return new ViewModel(array('form' => $form));			
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
		return new ViewModel(array('email' => $email));
	}	
	
	public function prepareData($user)
	{
		$user->setState(0);
		$user->setPasswordSalt($this->generateDynamicSalt());				
		$user->setPassword($this->encryptPassword(
								$this->getStaticSalt(), 
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
	
    public function getStaticSalt()
    {
		$staticSalt = '';
		
		$config = $this->getServiceLocator()->get('Config');
		$staticSalt = $config['static_salt'];		
        return $staticSalt;
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
		// $view = $this->getServiceLocator()->get('View');
		$hostname    = $_SERVER['HTTP_HOST'];
		$fullLink = "http://" . $hostname . $this->url()->fromRoute('csn-user/default', array(
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
		$fullLink = "http://" . $hostname . $this->url()->fromRoute('csn-user/default', array(
						'controller' => 'registration', 
						//'action' => 'confirm-email-change-password', 
                                                'action' => 'confirm-email-change-password', 
						'id' => $user->getRegistrationToken()));
		
		$this->getRequest()->getServer(); 
		$message->addTo($user->getEmail())
				->addFrom('praktiki@coolcsn.com')
				->setSubject('Please, confirm your request to change password!')
				->setBody("Please, follow ". $fullLink . " to confirm your request to change password.");
		$transport->send($message);
	}

	public function sendPasswordByEmail($username, $email, $password)
	{
	$hostname    = $_SERVER['HTTP_HOST'];
	$fullLink = "http://" . $hostname ."/csn-user/";
						
		$transport = $this->getServiceLocator()->get('mail.transport');
		$message = new Message();
		$this->getRequest()->getServer();  //Server vars
		$message->addTo($email)
				->addFrom('praktiki@coolcsn.com')
				->setSubject('Your password has been changed!')
				->setBody("Your password at  " . 
					$fullLink.
					' has been changed. For Username: ' . $username . ' and your new password is: ' .
					$password
				);
		$transport->send($message);		
	}
 
}