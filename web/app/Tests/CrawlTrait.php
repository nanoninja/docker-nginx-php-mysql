<?php
namespace Acme\Tests;

use Goutte\Client;

/**
 * Class WebTrait
 * @package Acme\Tests
 */
trait CrawlTrait {

    /**
     * return response code when crawling a given url
     * @param $url
     * @return mixed
     */
    function crawl($url)
    {
        $client = new Client();
        $client->request('GET', $url);
        $response_code = $client->getResponse()->getStatus();

        return $response_code;
    }

}
