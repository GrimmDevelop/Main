<?php

class UserTableSeeder extends Seeder {

    public function run() {
        $adminGroup = Sentry::createGroup(array(
            'name'        => 'Administrator',
            'permissions' => array(
                'users.view'    => 1,
                'users.create'  => 1,
                'users.edit'    => 1,
                'users.delete'  => 1,

                'letters.create'=> 1,
                'letters.edit'  => 1,
                'letters.delete'=> 1,

                'persons.create'=> 1,
                'persons.edit'  => 1,
                'persons.delete'=> 1,
            ),
        ));

        $letterModeratorGroup = Sentry::createGroup(array(
            'name' => 'Letter moderator',
            'permissions' => array(
                'letters.create'=> 1,
                'letters.edit'  => 1,
                'letters.delete'=> 1,
            ),
        ));

        $personModeratorGroup = Sentry::createGroup(array(
            'name' => 'Person moderator',
            'permissions' => array(
                'persons.create'=> 1,
                'persons.edit'  => 1,
                'persons.delete'=> 1,
            ),
        ));

        $admin = Sentry::register(array(
            'email'    => 'admin@admin.com',
            'username' => 'admin',
            'password' => 'admin',
            'activated'=> 1
        ));
        $admin->addGroup($adminGroup);

        $letterPersonModerator = Sentry::register(array(
            'email'    => 'letterperson@admin.com',
            'username' => 'letterperson',
            'password' => 'letterperson',
            'activated'=> 1
        ));
        $letterPersonModerator->addGroup($letterModeratorGroup);
        $letterPersonModerator->addGroup($personModeratorGroup);

        $letterModerator = Sentry::register(array(
            'email'    => 'letter@admin.com',
            'username' => 'letter',
            'password' => 'letter',
            'activated'=> 1
        ));
        $letterModerator->addGroup($letterModeratorGroup);

        $personModerator = Sentry::register(array(
            'email'    => 'person@admin.com',
            'username' => 'person',
            'password' => 'person',
            'activated'=> 1
        ));
        $personModerator->addGroup($personModeratorGroup);


    }

}
