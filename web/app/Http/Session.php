<?php
namespace Acme\Http;

/**
 * Class Session
 * @package Acme\Http
 */
class Session
{

    /**
     * @param $item
     * @return bool
     */
    public function has($name)
    {
        return (isset($_SESSION[$name]) ? true : false);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function put($name, $value)
    {
        $_SESSION[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function get($name)
    {
        return (isset($_SESSION[$name]) ? $_SESSION[$name] : false);
    }


    /**
     * @param $name
     * @return $this
     */
    public function forget($name)
    {
        unset($_SESSION[$name]);
        return $this;
    }

}
