<?php

use Phinx\Migration\AbstractMigration;

class SeedTestimonialTable extends AbstractMigration
{
    public function up()
    {
        $this->execute("
            insert into testimonials (title, testimonial, user_id, created_at)
            values
            ('Testimonial Title', 'Testimonial text', 1, '2015-08-26 19:15:43')
        ");
    }

    public function down()
    {

    }
}
