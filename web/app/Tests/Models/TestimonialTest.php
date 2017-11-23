<?php
namespace Acme\Tests;

use Acme\Models\Testimonial;

class TestimonialTest extends AcmeBaseIntegrationTest {

    public function testGetUserForTestimonialsTest()
    {
        $testimonial = Testimonial::find(1);
        $user = $testimonial->user();

        $actual = get_class($user);
        $expected = "Illuminate\\Database\\Eloquent\\Relations\\HasOne";
        $this->assertEquals($expected, $actual);
    }
}
