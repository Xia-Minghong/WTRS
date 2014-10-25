<?php
class TeacherTableSeeder extends Seeder {

    public function run()
    {
        DB::table('teacher')->delete();
        DB::table('teacher')->insert(array(
            'nric' => 'G1111111A',
            'full_name' => 'Chua Chu Kang',
            'short_name' => 'Chua CK',
            'designation' => 'LED',
            'grouping' => 1,
            'email_address' => 'cck@example.com',
            'contact_nos' => '88889999',
        ));
    }
}