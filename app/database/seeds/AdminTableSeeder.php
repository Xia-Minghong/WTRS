<?php
class AdminTableSeeder extends Seeder {

    public function run()
    {
        DB::table('admin')->delete();
        DB::table('admin')->insert(array(
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ));
    }
}