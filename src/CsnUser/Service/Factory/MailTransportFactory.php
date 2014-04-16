<?php
namespace CsnUser\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class MailTransportFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $transport = new Smtp();
        $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
        return $transport;
    }
}
