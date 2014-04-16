<?php
namespace CsnUser\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authenticationServiceString = $serviceLocator->get('csnuser_module_options')->getAuthenticationService();
        return $serviceLocator->get($authenticationServiceString);
    }
}
