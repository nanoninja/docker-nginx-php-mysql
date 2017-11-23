<?php
namespace Acme\Tests;

use Goutte\Client;

/**
 * Class PublicPagesTest
 * @package Acme\Tests
 */
class LoggedInTest extends AcmeBaseIntegrationTest {

    use CrawlTrait;

    public function testLoggedIn()
    {
        // we don't use the trait method here since we want our
        // test to span two page requests, and we need to have
        // the session persist on the remote server

        // create a web client and hit the login page
        $url = "http://localhost/login";
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $response_code = $client->getResponse()->getStatus();

        // we should get 200 back
        $this->assertEquals(200, $response_code);

        // select the form on the page and populate values
        // since we are using Goutte\Client, we don't need
        // to worry about parsing the HTML to find the csrf _token
        $form = $crawler->selectButton('Sign in')->form();

        $form->setValues([
            'email'    => 'me@here.ca',
            'password' => 'verysecret',
        ]);

        // submit the form
        $client->submit($form);
        $response_code_after_submit = $client->getResponse()->getStatus();

        // make sure the HTML page displayed (response code 200
        $this->assertEquals(200, $response_code_after_submit);

        // make sure we can get to the testimonial page
        $client->request('GET', 'http://localhost/add-testimonial');
        $response_code = $client->getResponse()->getStatus();

        $this->assertEquals(200, $response_code);

    }


    /**
     * Test trying to access add-testimonial when not logged in
     */
    function testAddTestimonialWhenNotLoggedIn()
    {
        $response_code = $this->crawl('http://localhost/add-testimonial');
        $this->assertEquals(404, $response_code);
    }
}
