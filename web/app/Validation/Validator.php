<?php
namespace Acme\Validation;

use Acme\Http\Request;
use Acme\Http\Response;
use Acme\Http\Session;
use Respect\Validation\Validator as Valid;

/**
 * Class Validator
 * @package Acme\Validation
 */
class Validator {

    protected $session;
    protected $isValid;
    protected $response;
    protected $request;

    /**
     * constructor
     */
    public function __construct(Request $request, Response $response, Session $session)
    {
        $this->session = new Session();
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @param $validation_data
     * @return array
     */
    public function check($validation_data)
    {

        $errors = [];

        foreach ($validation_data as $name => $value) {

            $rules = explode("|", $value);

            foreach ($rules as $rule) {
                $exploded = explode(":", $rule);

                switch ($exploded[0]) {
                    case 'min':
                        $min = $exploded[1];
                        if (Valid::stringType()->length($min)->Validate($this->request->input($name)) == false) {
                            $errors[] = $name . " must be at least " . $min . " characters long!";
                        }
                        break;

                    case 'email':
                        if (Valid::email()->Validate($this->request->input($name)) == false) {
                            $errors[] = $name . " must be a valid email!";
                        }
                        break;

                    case 'equalTo':
                        if (Valid::equals($this->request->input($name))->Validate($this->request->input($exploded[1])) == false) {
                            $errors[] = "Value does not match verification value!";
                        }
                        break;

                    case 'unique':
                        $model = "Acme\\models\\" . $exploded[1];
                        $table = new $model;
                        $results = $this->getRows($table, $name);
                        foreach ($results as $item) {
                            $errors[] = $this->request->input($name) . " already exists in this system!";
                        }
                        break;

                    default:
                        $errors[] = "No value found!";
                }
            }
        }

        return $errors;

    }


    /**
     * @param $rules
     * @return bool
     */
    public function validate($rules, $url)
    {
        $errors = $this->check($rules);

        if (sizeof($errors) > 0) {
            $this->redirectToPage($url, $errors);
        } else {
            $this->isValid = true;

            return true;
        }
    }


    /**
     * @return mixed
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param mixed $isValid
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @param $table
     * @param $name
     * @return mixed
     */
    public function getRows($table, $name)
    {
        $results = $table::where($name, '=', $this->request->input($name))->get();

        return $results;
    }

    /**
     * @param $url
     * @param $errors
     */
    protected function redirectToPage($url, $errors)
    {
        $this->session->put('_error', $errors);
        $this->isValid = false;
        $this->response->withInput();
        $this->response->withView($url)->render();
    }

}
