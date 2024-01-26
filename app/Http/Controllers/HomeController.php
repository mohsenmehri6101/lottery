<?php

namespace App\Http\Controllers;

use Faker\Factory as FakerFactory;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Services\ImageService;

class HomeController extends Controller
{
    public function index()
    {
        /** @var Gym $gym */
        $gym = Gym::query()->inRandomOrder()->first();

        $faker = FakerFactory::create();

        $faker = \Faker\Factory::create('fa_IR');
        $image_directory = public_path('faker_avatars/');
        $image_files = glob($image_directory . '*.{jpg,jpeg}', GLOB_BRACE);
        $random_image = $faker->randomElement($image_files);

        $l =  ImageService::saveImage(image:$random_image,model: $gym);
        dd($l);
        // return view('home.index');
    }

}
