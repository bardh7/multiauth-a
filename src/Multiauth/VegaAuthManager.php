<?php

namespace Autoluminescent\Multiauth;

class VegaAuthManager
{
    protected $app;

    protected $guards = [];

    protected $currentGuard = [];

    protected $guardDomain;

    public function __construct($app, $guards)
    {
        $this->app = $app;
        $this->guards = $guards;
        $this->guardDomain = $this->resolveGuardDomain();
    }

    public function view($key)
    {
        return $this->guards[$this->guardDomain]['views'][$key];
    }

    public function resolveGuardName()
    {
        return $this->guards[$this->guardDomain]['name'];
    }

    public function resolveGuardDomain()
    {
        $requestSegment = request()->segment(1);

        if (array_key_exists($requestSegment, $this->guards)) {
            return $requestSegment;
        }

        return 'web';
    }

    public function guard()
    {
        $this->currentGuard = $this->guards[$this->guardDomain];

        return $this;
    }

    public function get()
    {
        return $this->currentGuard;
    }

    public function name()
    {
        return $this->currentGuard['name'];
    }

    public function domain()
    {
        return $this->currentGuard['domain'];
    }

    public function prefix()
    {
        return $this->currentGuard['prefix'];
    }

    public function redirectAfterLogin()
    {
        return $this->currentGuard['refirect_after_login'];
    }

    public function getByKey($key)
    {
        if (isset($this->currentGuard[$key])) {
            return $this->currentGuard[$key];
        }

        return false;
    }
}
