<?php
namespace Acme\Tests;

use Acme\Models\User;

class UserTest extends AcmeBaseIntegrationTest {

    public function testGetTestimonialsForUser()
    {
        $user = User::find(1);
        $testimonials = $user->testimonials();

        $actual = get_class($testimonials);
        $expected = "Illuminate\\Database\\Eloquent\\Relations\\HasMany";
        $this->assertEquals($expected, $actual);
    }
}
