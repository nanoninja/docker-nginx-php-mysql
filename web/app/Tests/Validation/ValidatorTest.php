<?php
namespace Acme\Tests\Validation;

use Acme\Http\Request;
use Acme\Http\Response;
use Acme\Http\Session;
use Acme\Validation\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var Response|\PHPUnit_Framework_MockObject_MockObject
     */
    private $response;

    /**
     * @var Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $session;

    protected function setUp()
    {
        parent::setUp();

        $this->response = $this->createMock(Response::class);
        $this->session  = $this->createMock(Session::class);
        $this->request  = $this->createMock(Request::class);
    }

    public function testGetIsValidReturnsTrue()
    {
        $validator = new Validator($this->request, $this->response, $this->session);

        $validator->setIsValid(true);

        $this->assertTrue($validator->getIsValid());
    }

    public function testGetIsValidReturnsFalse()
    {
        $validator = new Validator($this->request, $this->response, $this->session);
        $validator->setIsValid(false);

        $this->assertFalse($validator->getIsValid());
    }

    public function testCheckForMinStringLengthWithValidData()
    {
        $this->request->expects(static::once())
            ->method('input')
            ->with('mintype')
            ->willReturn('some_value');

        $validator = new Validator($this->request, $this->response, $this->session);
        $errors    = $validator->check(['mintype' => 'min:3']);

        $this->assertCount(0, $errors);
    }

    public function testCheckForMinStringLengthWithInValidData()
    {
        $this->request->expects(static::once())
            ->method('input')
            ->with('mintype')
            ->willReturn('s');

        $validator = new Validator($this->request, $this->response, $this->session);
        $errors    = $validator->check(['mintype' => 'min:3']);

        $this->assertCount(1, $errors);
    }

    public function testCheckForEmailWithValidData()
    {
        $this->request->expects(static::once())
            ->method('input')
            ->with('email')
            ->willReturn('test@test.com');

        $validator = new Validator($this->request, $this->response, $this->session);
        $errors    = $validator->check(['email' => 'email']);

        $this->assertCount(0, $errors);
    }

    public function testCheckForEmailWithInValidData()
    {
        $this->request->expects(static::once())
            ->method('input')
            ->with('email')
            ->willReturn('test@test');

        $validator = new Validator($this->request, $this->response, $this->session);
        $errors    = $validator->check(['email' => 'email']);

        $this->assertCount(1, $errors);
    }
}