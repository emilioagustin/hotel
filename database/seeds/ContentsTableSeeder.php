<?php

use Illuminate\Database\Seeder;
use App\Content;

class ContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Content::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 8; $i++) {
            Content::create([
                'name' => $faker->name(),
                'order' => $i+1,
                'status' => (bool)random_int(0, 1),
            ]);
        }
    }
}
