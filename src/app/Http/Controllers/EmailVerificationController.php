<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function notice(Request $request) { 
        return response("Email verification link has been sent to your email address. Please verify your email");
    }

    public function verify(EmailVerificationRequest $request) { 
        $request->fulfill();
        return response("Successfully verified email");
    }

    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response("Verification link sent");
    }

}
