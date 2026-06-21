<?php

namespace App\Http\Controllers\Auth;

class GoogleAuthController extends GoogleController
{
    public function redirect()
    {
        return $this->redirectToGoogle();
    }

    public function callback()
    {
        return $this->handleGoogleCallback();
    }
}
