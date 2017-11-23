<?php
namespace Acme\Controllers;

use Acme\Email\SendEmail;
use Acme\Models\User;
use Acme\Models\UserPending;
use Acme\Validation\Validator;

/**
 * Class RegisterController
 * @package Acme\Controllers
 */
class RegisterController extends BaseControllerWithDI {

    /**
     * Show the registration page
     */
    public function getShowRegisterPage()
    {
        $this->response->withView('register')->render();
    }


    /**
     * Handle post of registration form
     */
    public function postShowRegisterPage()
    {
        $rules = [
            'first_name'   => 'min:3',
            'last_name'    => 'min:3',
            'email'        => 'email|equalTo:verify_email|unique:User',
            'verify_email' => 'email',
            'password'     => 'min:3|equalTo:verify_password',
        ];

        $validator = new Validator($this->request, $this->response, $this->session);
        $valid = $validator->validate($rules, '/register');

        if ($valid) {
            $user = new User();
            $user->first_name = $this->request->input('first_name');
            $user->last_name = $this->request->input('last_name');
            $user->email = $this->request->input('email');
            $user->password = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
            $user->save();

            $token = md5(uniqid(rand(), true)) . md5(uniqid(rand(), true));
            $user_pending = new UserPending;
            $user_pending->token = $token;
            $user_pending->user_id = $user->id;
            $user_pending->save();

            $message = $this->blade->render('emails.welcome-email',
                ['token' => $token]
            );

            SendEmail::sendEmail($user->email, "Welcome to Acme", $message);

            $this->response->withMessage('Registration successful!')->redirectTo("/success");
        }
    }


    /**
     * Verify an account
     */
    public function getVerifyAccount()
    {
        $user_id = 0;
        $token = $_GET['token'];

        // look up the token
        $user_pending = UserPending::where('token', '=', $token)->first();

        if ($user_pending)
            $user_id = $user_pending->user_id;

        if ($user_id > 0) {
            $user = User::find($user_id);
            $user->active = 1;
            $user->save();
            UserPending::where('token', '=', $token)->delete();

            $this->response->redirectTo("/account-activated");
        } else {
            $this->response
                ->withView('page-not-found')
                ->withError("Page not found!")
                ->withResponseCode(404)
                ->render();
        }
    }

}
