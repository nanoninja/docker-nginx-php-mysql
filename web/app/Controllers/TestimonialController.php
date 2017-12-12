<?php
namespace Acme\Controllers;

use Acme\Auth\LoggedIn;
use Acme\Models\Testimonial;
use Acme\Validation\Validator;

/**
 * Class TestimonialController
 * @package Acme\Controllers
 */
class TestimonialController extends BaseControllerWithDI {

    /**
     * Show the testimonials page
     */
    public function getShowTestimonials()
    {
        $testimonials = Testimonial::all();

        $this->response
            ->withView('testimonials')
            ->with('testimonials', $testimonials)
            ->render();
    }

    /**
     * Show the add testimonials page
     */
    public function getShowAdd()
    {
        $this->response
            ->withView('add-testimonial')
            ->render();
    }


    /**
     * Handle new posted testmonial
     */
    public function postShowAdd()
    {
        $rules = [
            'title'       => 'min:3',
            'testimonial' => 'min:10',
        ];

        $validator = new Validator($this->request, $this->response);
        $valid = $validator->validate($rules, '/add-testimonial');

        if ($valid) {
            $testimonial = new Testimonial;
            $testimonial->title = $this->request->input('title');
            $testimonial->testimonial = $this->request->input('testimonial');
            $testimonial->user_id = LoggedIn::user()->id;
            $testimonial->save();
            $this->response->redirectTo('/testimonial-saved');
        }
    }
}
