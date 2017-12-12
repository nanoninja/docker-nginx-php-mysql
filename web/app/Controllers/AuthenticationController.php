<?php
namespace Acme\Controllers;

use Acme\Models\User;
use Acme\Validation\Validator;

/**
 * Class AuthenticationController
 * @package Acme\Controllers
 */
class AuthenticationController extends BaseControllerWithDI {

    /**
     * Show the login page
     */
    public function getShowLoginPage()
    {
        $this->response
            ->withView('login')
            ->render();
    }


    /**
     * Handle posted login data
     */
    public function postShowLoginPage()
    {
        if (!$this->signer->validateSignature($this->request->post['_token'])) {
            header('HTTP/1.0 400 Bad Request');
            exit;
        }

        $rules = [
            'email'    => 'email|min:3',
            'password' => 'min:3',
        ];

        $validator = new Validator($this->request, $this->response, $this->session);
        $valid = $validator->validate($rules, '/login');

        if ($valid) {
            $okay = true;
            $email = $this->request->post['email'];
            $password = $this->request->post['password'];

            $user = User::where('email', '=', $email)
                ->first();

            if ($user != null) {
                if (!password_verify($password, $user->password)) {
                    $okay = false;
                }
            } else {
                $okay = false;
            }

            if ($user && $user->active == 0) {
                $okay = false;
            }

            if ($okay) {
                $this->session->put('user', $user);
                $this->response->withMessage("Successfully logged in")->redirectTo("/");
            } else {
                $this->session->put('_error', 'Invalid login!!');

                $this->response->redirectTo('/login');
            }
        }
    }


    /**
     * Logout of the app
     */
    public function getLogout()
    {
        unset($_SESSION['user']);
        $this->response->withMessage("Logged out!")->redirectTo("/login");
    }

}
