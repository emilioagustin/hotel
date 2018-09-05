<?php

use Illuminate\Database\Seeder;
use App\ContentImage;

class ContentImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.protected $fillable = ['content_id', 'language', 'title', 'body'];
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        // ContentImage::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 8; $i++) {
            $filepath = public_path("img/".($i+1));
            if(!File::exists($filepath)){
                File::makeDirectory($filepath);
            }
            ContentImage::create([
                'content_id' => $i+1,
                'image_path' => '' //$faker->image($dir = $filepath, $width = 1024, $height = 1024, null, false)
            ]);
        }
    }
}
