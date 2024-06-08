<?php

namespace App\Utils;

class Auth
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isAdminUser(): bool {
        $loggedUser = $this->session->get('userSession');
        if(!$loggedUser || $loggedUser['roleId'] != 1) {
            return false;
        }
        return true;
    }
}