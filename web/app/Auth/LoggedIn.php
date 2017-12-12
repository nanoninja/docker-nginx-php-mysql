<?php
namespace Acme\Auth;

use Acme\Http\Session;

/**
 * Class LoggedIn
 * @package Acme\Auth
 */
class LoggedIn {

    static $session;

    public function __construct(Session $session)
    {
        self::$session = $session;
    }


    /**
     * @return bool|Acme\Models\User
     */
    public static function user()
    {
        if (self::$session->has('user')) {
            $user = self::$session->get('user');

            return $user;
        } else {
            return false;
        }
    }
}
