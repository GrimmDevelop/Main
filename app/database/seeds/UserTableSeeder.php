<?php

class UserTableSeeder extends Seeder {

    public function run() {
        Sentry::register(array(
            'email'    => 'admin@admin.com',
            'username' => 'admin',
            'password' => 'admin',
            'activated'=> 1
        ));
    }

}
