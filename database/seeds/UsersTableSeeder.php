<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = database_path() . '/export.csv';
        $csv = Reader::createFromPath($file);
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            \DB::table('users')->insert([
                'id' => $row['user_id'],
                'onboarding_percentage' => (int) $row['onboarding_perentage'],
                'count_applications' => (int) $row['count_applications'],
                'count_accepted_applications' => (int) $row['count_accepted_applications'],
                'created_at' => new DateTime($row['created_at']),
            ]);
        };
    }
}
