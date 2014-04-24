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

namespace CsnUser\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class ErrorViewFactory implements FactoryInterface
{
    private $serviceLocator;
  
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Create error view
     *
     * Method to create error view to display possible exceptions
     *
     * @return ViewModel
     */
    public function createErrorView($errorMessage, $exception, $displayExceptions = false, $displayNavMenu = false )
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
}
