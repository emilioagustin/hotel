<?php

use Faker\Generator as Faker;

$factory->define(App\Content::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'status' => 1,
        'order' => 1
    ];
});

$factory->define(App\ContentTranslation::class, function (Faker $faker) use ($factory) {
    return [
        'content_id' => factory(App\Content::class)->create()->id,
        'language' => 'fk',
        'title' => $faker->name, 
        'body' => $faker->randomHtml(3, 6)
    ];
});

$factory->define(App\ContentImage::class, function (Faker $faker) use ($factory) {
    $id = 100;
    $filepath = public_path("img/".($id));
    if(!File::exists($filepath)){
        File::makeDirectory($filepath);
    }
    return [
        'id' => $id, 
        'content_id' => factory(App\Content::class)->create()->id,
        'image_path' => ''//$faker->image($dir = $filepath, $width = 1024, $height = 1024, null, false),
    ];
});