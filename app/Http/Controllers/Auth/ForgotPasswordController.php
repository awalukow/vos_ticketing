<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    // Show the form to request a password reset
    public function showLinkRequestForm()
    {
        return view('auth.forgotPassword');
    }

    // Where to redirect after sending reset link
    protected $redirectTo = RouteServiceProvider::HOME;
}