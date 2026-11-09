<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\GoogleAuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleAuthController extends Controller
{
    protected GoogleAuthService $googleAuthService;

    public function __construct(GoogleAuthService $googleAuthService)
    {
        $this->googleAuthService = $googleAuthService;
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return $this->googleAuthService->redirect('login');
    }

    public function redirectToGoogleRegister(): RedirectResponse
    {
        return $this->googleAuthService->redirect('register');
    }

    public function redirectToGoogleConnect(): RedirectResponse
    {
        return $this->googleAuthService->redirect('connect');
    }

    public function handleGoogleCallback(): \Illuminate\Http\RedirectResponse
    {
        return $this->googleAuthService->handleCallback();
    }
}
