<?php
namespace Acme\Controllers;

use Acme\Models\Page;

/**
 * Class PageController
 * @package Acme\Controllers
 */
class PageController extends BaseControllerWithDI {

    public $page;

    /**
     * Show the home page
     * @return html
     */
    public function getShowHomePage()
    {
        return $this->response
            ->withView('home')
            ->render();
    }


    /**
     * Show a generic page from db
     * @return html
     */
    public function getShowPage()
    {
        // extract page name from the url
        $target = $this->getUri();

        // find matching page in the db
        $this->page = Page::where('slug', '=', $target)->first();

        // look up page content
        if ($this->page) {
            $browser_title = $this->page->browser_title;
            $page_content = $this->page->page_content;
            $page_id = $this->page->id;
        }

        if (!isset($browser_title)) {
            $this->getShow404();
            return true;
        }

        return $this->response
            ->with('browser_title', $browser_title)
            ->with('page_content', $page_content)
            ->with('page_id', $page_id)
            ->withView('generic-page')
            ->render();
    }


    /**
     * show 404 page
     */
    public function getShow404()
    {
        return $this->response
            ->withView('page-not-found')
            ->withError("Page not found!")
            ->withResponseCode(404)
            ->render();
    }


    /**
     * @param $stringToSlug
     * @param string $separator
     * @return mixed|string
     */
    public function makeSlug($stringToSlug, $separator = "-")
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $stringToSlug);
        $slug = preg_replace("%[^-/+|\w ]%", '', $slug);
        $slug = strtolower(trim($slug));
        $slug = preg_replace("/[\/_|+ -]+/", $separator, $slug);

        return $slug;
    }


    /**
     * @return mixed
     */
    protected function getUri()
    {
        $uri = explode("/", $this->request->server['REQUEST_URI']);
        return $uri[1];
    }

}
