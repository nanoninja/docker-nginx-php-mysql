<?php
namespace Acme\Tests;

/**
 * Class PageControllerTest
 * @package Acme\Tests
 */
class PageControllerTest extends \PHPUnit_Framework_TestCase {

    protected $request;
    protected $response;
    protected $session;
    protected $blade;
    protected $signer;

    /**
     * Set up our necessary constructor objects as mocks
     */
    protected function setUp()
    {
        $this->signer = $this->getMockBuilder('Kunststube\CSRFP\SignatureGenerator')
            ->setConstructorArgs(['abc134'])
            ->getMock();

        $this->request = $this->getMockBuilder('Acme\Http\Request')
            ->getMock();

        $this->session = $this->getMockBuilder('Acme\Http\Session')
            ->getMock();

        $this->blade = $this->getMockBuilder('duncan3dc\Laravel\BladeInstance')
            ->setConstructorArgs(['abc', 'abc'])
            ->getMock();

        $this->response = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer, $this->blade, $this->session])
            ->getMock();
    }


    /**
     * @param $original
     * @param $expected
     *
     * @dataProvider providerTestMakeSlug
     */
    public function testMakeSlug($original, $expected)
    {
        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $this->response, $this->session,
                $this->signer, $this->blade])
            ->setMethods(null)
            ->getMock();

        $actual = $controller->makeSlug($original);

        $this->assertEquals($expected, $actual);
    }


    /**
     * @return array
     */
    public function providerTestMakeSlug()
    {
        return [
            ["Hello World", "hello-world"],
            ["Goodbye, cruel world!", "goodbye-cruel-world"],
            ["What about an & and a ?", "what-about-an-and-a"],
            ["It should also handle é", "it-should-also-handle-e"],
        ];
    }
}
