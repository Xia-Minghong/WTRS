<?php
class AdminTableSeeder extends Seeder {

    /**
     * Create a default admin account
     * Username: admin
     * Passowrd: admin
     */
    public function run()
    {
        DB::table('admin')->delete();
        DB::table('admin')->insert(array(
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ));
    }
}