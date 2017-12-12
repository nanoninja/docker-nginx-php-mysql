<?php
namespace Acme\Controllers;

use Acme\Models\Page;
use Cocur\Slugify\Slugify;

/**
 * Class AdminController
 * @package Acme\Controllers
 */
class AdminController extends BaseControllerWithDI {

    /**
     * Saved edited page; called via ajax
     * @return string
     */
    public function postSavePage()
    {
        $okay = true;

        $page_id = $this->request->input('page_id');
        $page_content = $this->request->input('thedata');

        if ($page_id > 0) {
            $page = Page::find($page_id);
        } else {
            $page = new Page;
            $slugify = new Slugify;
            $browser_title = $this->request->input('broswer_title');
            $page->browser_title = $browser_title;
            $page->slug = $slugify->slugify($browser_title);
            $results = Page::where('slug', '=', $slugify->slugify($browser_title))->first();

            if ($results)
                $okay = false;
        }

        $page->page_content = $page_content;

        if ($okay) {
            $page->save();
            echo "OK";
        } else {
            echo "Browser title is already in use!";
        }
    }


    /**
     * Add a page to db
     */
    public function getAddPage()
    {
        $page_id = 0;
        $page_content = "Enter your content here";
        $browser_title = "";
        return $this->response
            ->with('page_id', $page_id)
            ->with('browser_title', $browser_title)
            ->with('page_content', $page_content)
            ->withView('generic-page')
            ->render();
    }

}
