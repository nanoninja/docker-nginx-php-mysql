<?php
namespace Acme\Tests\Validation;

use Acme\Http\Request;
use Acme\Http\Response;
use Acme\Http\Session;
use Acme\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var Session
     */
    private $session;

    protected function setUp()
    {
        parent::setUp();

        $this->response = $this->createMock(Response::class);
        $this->session  = $this->createMock(Session::class);
    }

    public function testGetIsValidReturnsTrue()
    {
        $request = new Request();
        $validator = new Validator($request, $this->response, $this->session);
        $validator->setIsValid(true);

        $this->assertTrue($validator->getIsValid());
    }

    public function testGetIsValidReturnsFalse()
    {
        $request = new Request();
        $validator = new Validator($request, $this->response, $this->session);
        $validator->setIsValid(false);

        $this->assertFalse($validator->getIsValid());
    }

    public function testCheckForMinStringLengthWithValidData()
    {
        $_REQUEST['mintype'] = 'some_value';
        $request = new Request();
        $validator = new Validator($request, $this->response, $this->session);

        $errors = $validator->check(['mintype' => 'min:3']);

        $this->assertCount(0, $errors);
    }
}