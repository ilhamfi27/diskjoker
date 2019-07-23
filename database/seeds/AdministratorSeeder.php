<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $administrators = [
            [
                'id' => 1,
                'name' => 'admin1',
                'email' => 'dummy_admin1@world.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'id' => 2,
                'name' => 'admin2',
                'email' => 'dummy_admin2@world.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'id' => 3,
                'name' => 'admin3',
                'email' => 'dummy_admin3@world.com',
                'password' => bcrypt('12345678'),
            ],
        ];

        $administratorBiodata = [
            [
                'nickname' => 'Admin 1',
                'level' => 'admin',
                'user_id' => '1',
            ],
            [
                'nickname' => 'Admin 2',
                'level' => 'admin',
                'user_id' => '2',
            ],
            [
                'nickname' => 'Admin 3',
                'level' => 'rm',
                'user_id' => '3',
            ],
        ];

        foreach ($administrators as $idx => $data) {
            \App\User::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            \App\UserBiodata::create([
                'nickname' => $administratorBiodata[$idx]['nickname'],
                'level' => $administratorBiodata[$idx]['level'],
                'user_id' => $administratorBiodata[$idx]['user_id'],
            ]);
        }
    }
}
