<?php
namespace Acme\Tests;

use Acme\Controllers\PageController;
use Acme\Http\Request;

/**
 * Class PageControllerIntegrationTest
 * @package Acme\Tests
 */
class PageControllerIntegrationTest extends AcmeBaseIntegrationTest {


    /**
     * Override setUp in parent and set value in $_SERVER
     */
    public function setUp()
    {
        $_SERVER['REQUEST_URI'] = '/about-acme';
        parent::setUp();
    }

    /**
     * Test showing home page
     */
    public function testGetHomePage()
    {
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        $resp->method('render')
            ->willReturn(true);

        $controller = new PageController($this->request, $resp,
            $this->session, $this->signer, $this->blade);

        $controller->getShowHomePage();

        // should have view of home
        $expected = "home";
        $actual = \PHPUnit_Framework_Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);
    }


    /**
     * Test showing a page
     */
    public function testGetShowPage()
    {
        // create a mock of Response and make render method a stub
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        // override render method to return true
        $resp->method('render')
            ->willReturn(true);

        // mock the controller and make getUri a stub
        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $resp, $this->session,
                $this->signer, $this->blade])
            ->setMethods(['getUri'])
            ->getMock();

        // orverride getUri to return just the slug from the uri
        $controller->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('about-acme'));

        // call the method we want to test
        $controller->getShowPage();

        // we expect to get the $page object with browser_title set to "About Acme"
        $expected = "About Acme";
        $actual = $controller->page->browser_title;

        // run assesrtion for browser title/page title
        $this->assertEquals($expected, $actual);

        // should have view of generic-page
        $expected = "generic-page";
        $actual = \PHPUnit_Framework_Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);

        // should have page_id of 1
        $expected = 1;
        $actual = $controller->page->id;
        $this->assertEquals($expected, $actual);
    }


    /**
     * Test page with invalid data
     */
    public function testGetShowPageWithInvalidData()
    {
        // create a mock of Response and make render method a stub
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        // override render method to return true
        $resp->method('render')
            ->willReturn(true);

        // mock the controller and make getUri a stub
        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $resp, $this->session,
                $this->signer, $this->blade])
            ->setMethods(['getUri', 'getShow404'])
            ->getMock();

        // orverride getUri to return just the slug from the uri
        $controller->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue('missing-page'));

        $controller->expects($this->once())
            ->method('getShow404')
            ->will($this->returnValue(true));

        // call the method we want to test
        $result = $controller->getShowPage();

        // should get true back if we called 404
        $this->assertTrue($result);

    }


    /**
     * Test showing page not found
     */
    public function testGetShow404()
    {
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        $resp->method('render')
            ->willReturn(true);

        $controller = new PageController($this->request, $resp,
            $this->session, $this->signer, $this->blade);

        $controller->getShow404();

        // should have view of page-not-found
        $expected = "page-not-found";
        $actual = \PHPUnit_Framework_Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);
    }


    /**
     * Test getUri
     */
    public function testGetUri()
    {
        // create a mock of Response and make render method a stub
        $resp = $this->getMockBuilder('Acme\Http\Response')
            ->setConstructorArgs([$this->request, $this->signer,
                $this->blade, $this->session])
            ->setMethods(['render'])
            ->getMock();

        // override render method to return true
        $resp->method('render')
            ->willReturn(true);

        $controller = $this->getMockBuilder('Acme\Controllers\PageController')
            ->setConstructorArgs([$this->request, $resp, $this->session,
                $this->signer, $this->blade])
            ->setMethods(null)
            ->getMock();

        // call the method we want to test
        $controller->getShowPage();

        // we expect to get the $page object with browser_title set to "About Acme"
        $expected = "About Acme";
        $actual = $controller->page->browser_title;

        // run assesrtion for browser title/page title
        $this->assertEquals($expected, $actual);

        // should have view of generic-page
        $expected = "generic-page";
        $actual = \PHPUnit_Framework_Assert::readAttribute($resp, 'view');
        $this->assertEquals($expected, $actual);

        // should have page_id of 1
        $expected = 1;
        $actual = $controller->page->id;
        $this->assertEquals($expected, $actual);

    }
}
