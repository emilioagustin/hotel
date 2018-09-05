<?php

use Illuminate\Database\Seeder;
use App\ContentTranslation;

class ContentTranslationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.protected $fillable = ['content_id', 'language', 'title', 'body'];
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        ContentTranslation::truncate();

        $faker = \Faker\Factory::create();
        $langs = array('ca', 'es', 'en', 'fr');

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 8; $i++) {
            ContentTranslation::create([
                'content_id' => $i+1,
                'language' => $langs[array_rand($langs)],
                'title' => $faker->name,
                'body' => $faker->randomHtml(3, 6),
            ]);
        }
    }
}
