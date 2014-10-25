<?php
class McScoreTableSeeder extends Seeder {

    public function run()
    {
        DB::table('mc_score')->delete();
        DB::table('mc_score')->insert(array(
            'short_name' => 'Agnes Lee',
            'mc_score' => -2,
        ));
    }
}