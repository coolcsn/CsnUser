<?php

namespace CsnUser\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $loginRedirectRoute = 'user';

    /**
     * @var string
     */
    protected $logoutRedirectRoute = 'user';
    
    /**
     * @var string
     */
    protected $static_salt = 'aFGQ475SDsdfsaf2342';
    
    /**
     * @var bool
     */
    protected $navMenu = true;

    /**
     * set login redirect route
     *
     * @param string $loginRedirectRoute
     * @return ModuleOptions
     */
    public function setLoginRedirectRoute($loginRedirectRoute)
    {
        $this->loginRedirectRoute = $loginRedirectRoute;
        return $this;
    }

    /**
     * get login redirect route
     *
     * @return string
     */
    public function getLoginRedirectRoute()
    {
        return $this->loginRedirectRoute;
    }

    /**
     * set logout redirect route
     *
     * @param string $logoutRedirectRoute
     * @return ModuleOptions
     */
    public function setLogoutRedirectRoute($logoutRedirectRoute)
    {
        $this->logoutRedirectRoute = $logoutRedirectRoute;
        return $this;
    }

    /**
     * get logout redirect route
     *
     * @return string
     */
    public function getLogoutRedirectRoute()
    {
        return $this->logoutRedirectRoute;
    }

    /**
     * set static salt
     *
     * @param string $staticSalt
     * @return ModuleOptions
     */
    public function setStaticSalt($staticSalt)
    {
        $this->static_salt = $staticSalt;
        return $this;
    }

    /**
     * get static salt
     *
     * @return string
     */
    public function getStaticSalt()
    {
        return $this->static_salt;
    }
    
    /**
     * set visibility of navigation menu
     *
     * @param bool $navMenu
     * @return ModuleOptions
     */
    public function setNavMenu($navMenu)
    {
        $this->navMenu = $navMenu;
        return $this;
    }

    /**
     * get visibility of navigation menu
     *
     * @return string
     */
    public function getNavMenu()
    {
        return $this->navMenu;
    }
}
