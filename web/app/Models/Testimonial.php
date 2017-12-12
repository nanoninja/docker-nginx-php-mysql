<?php
namespace Acme\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Testimonial extends Eloquent {

    public function user()
    {
        return $this->hasOne('Acme\Models\User');
    }
}
