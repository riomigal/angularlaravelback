<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Auth\BaseController;
use App\Models\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class VerificationApiController extends BaseController
{
    use VerifiesEmails;

    /**
     * Mark the user as verified
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {

        // Check signature, if not valid send to frontend verifying account
        if (!$request->hasValidSignature()) {
            return redirect(config('app.front_url'));
        }


        $user = User::findOrFail($request['id']);
        $user->email_verified_at = date("Y-m-d g:i:s");
        $user->save();

        return redirect(config('app.front_url'));
    }

    /**
     * Resend the email verification (User must have a Bearer Token to use this -> Login and resend)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // Handle user who already verified account
            if ($request->user()->hasVerifiedEmail()) {
                return $this->sendError('User already have verified email!', ['email_sent_error' => 'Email already verified. Login again.'], 409);
            }
            // Resend Email Verification Mail
            $request->user()->sendApiEmailVerificationNotification();
            return $this->sendResponse(['email_sent' => ['Email send to ' . $request->email]], 'The notification has been resubmitted');
        } else {
            return $this->sendError('User not found!', ['email_sent_error' => 'User not found are you using the correct credentials?'], 403);
        }
    }
}