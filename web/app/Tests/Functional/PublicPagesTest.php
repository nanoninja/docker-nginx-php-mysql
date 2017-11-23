<?php
namespace Acme\Tests;

/**
 * Class PublicPagesTest
 * @package Acme\Tests
 */
class PublicPagesTest extends AcmeBaseIntegrationTest {

    use CrawlTrait;

    /**
     * Test public pages
     * @dataProvider provideUrls
     */
    function testPages($urlToTest)
    {
        $response_code = $this->crawl('http://localhost' . $urlToTest);
        $this->assertEquals(200, $response_code);
    }


    /**
     * Test page not found
     */
    function testPageNotFound()
    {
        $response_code = $this->crawl('http://localhost/asdf');
        $this->assertEquals(404, $response_code);
    }


    /**
     * Test showing login page
     */
    function testLoginPage()
    {
        $response_code = $this->crawl('http://localhost/login');
        $this->assertEquals(200, $response_code);
    }


    /**
     * @return array
     */
    public function provideUrls()
    {
        return [
            ['/'],
            ['/about-acme'],
            ['/account-activated'],
            ['/success'],
        ];
    }
}
