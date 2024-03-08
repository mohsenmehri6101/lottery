<?php

namespace Modules\Gym\Database\Seeders;

use App\Permissions\RolesEnum;
use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Entities\UserDetail;
use Modules\Authorization\Entities\Role;
use Modules\Authorization\Services\RoleService;
use Modules\Geographical\Entities\City;
use Illuminate\Http\UploadedFile;
use Modules\Gym\Entities\Attribute;
use Modules\Gym\Entities\AttributePrice;
use Modules\Gym\Entities\Category;
use Modules\Gym\Entities\CommonComplaint;
use Modules\Gym\Entities\Complaint;
use Modules\Gym\Entities\Reserve;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Entities\Image;
use Modules\Gym\Entities\Keyword;
use Modules\Gym\Entities\Sport;
use Modules\Gym\Entities\Tag;
use Faker\Factory as FakerFactory;
use Modules\Gym\Services\ImageService;
use Modules\Payment\Entities\Factor;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Services\FactorService;
use Modules\Payment\Services\PaymentService;
use Modules\Slider\Entities\Slider;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class GymDatabaseSeeder extends Seeder
{
    public static function helperFunctionSaveImage(Gym $gym, $images = []): void
    {
        if (count($images)) {
            foreach ($images as $image) {
                if ($image) {
                    $image = new UploadedFile($image, basename($image));
                    # delete  before
                    $name_file = ImageService::setNameFile($image);
                    // todo convert image change image size and with and height
                    $path_image = $image->storeAs('gyms', $name_file);
                    if ($path_image) {
                        $imageModel = new Image(['url' => $path_image, 'image' => $name_file]);
                        $gym->images()->save($imageModel);
                    }
                }
            }
        }
    }

    public static function helperFunctionSaveAvatar(User $user, $image): void
    {
        $image = new UploadedFile($image, basename($image));
        # delete avatar before
        $name_file = ImageService::setNameFile($image);
        $path_image = $image->storeAs('avatars', $name_file);
        if ($path_image) {
            $user->avatar = $path_image;
            $user->save();
        }
    }

    public static function helperFunctionKeywordFake(): void
    {
        $keywords = PersianDataProvider::$keywords;
        foreach ($keywords as $keyword) {
            if (Keyword::query()->where('keyword', $keyword)->doesntExist()) {
                Keyword::query()->create(['keyword' => $keyword]);
            }
        }
    }

    public static function helperFunctionTagFake(): void
    {
        $tags = PersianDataProvider::$tags;
        foreach ($tags as $tag) {
            if (Tag::query()->where('tag', $tag)->doesntExist()) {
                Tag::query()->create(['tag' => $tag]);
            }
        }
    }

    public static function helperFunctionCategoryFake(): void
    {
        # $faker = FakerFactory::create();
        $categories = PersianDataProvider::$categories;
        foreach ($categories as $index => $category) {
            # $parent = null;
            # if ($index < 3) {
            #     $list_categories_parent = [null, null, null, null, Category::query()->inRandomOrder()->first()->id];
            #     $parent = $faker->randomElement($list_categories_parent);
            # } else {
            #     $parent = null;
            # }
            Category::query()->create(['name' => $category/*, 'parent' => $parent*/]);
        }
    }

    public static function helperFunctionComplaintFake($number = 40): void
    {
        $faker = FakerFactory::create();
        for ($i = 0; $i < $number; $i++) {
            $status_fake_random = $faker->randomElement([Complaint::status_unknown, Complaint::status_not_checked, Complaint::status_reviewed]);
            Complaint::query()->create([
                'user_id' => User::query()->inRandomOrder()->first()->id,
                'description' => $faker->text(),
                'status' => $status_fake_random,
                'user_creator' => User::query()->inRandomOrder()->first()->id,
                'user_editor' => User::query()->inRandomOrder()->first()->id,
                'factor_id' => Factor::query()->inRandomOrder()->first()->id,
                'gym_id' => Gym::query()->inRandomOrder()->first()->id,
                'reserve_id' => Reserve::query()->inRandomOrder()->first()->id,
                'reserve_template_id' => ReserveTemplate::query()->inRandomOrder()->first()->id,
            ]);
        }
    }

    public static function helperFunctionCommonComplaintFake($number = 40): void
    {
        $faker = FakerFactory::create();
        for ($i = 0; $i < $number; $i++) {
            CommonComplaint::query()->insert([
                'text' => $faker->text(),
            ]);
        }

    }

    public static function helperFunctionAttributeFake(): void
    {
        $attributes = PersianDataProvider::$attributes;
        foreach ($attributes as $attribute) {
            if (Attribute::query()->where('name', $attribute)->doesntExist()) {
                Attribute::query()->create(['name' => $attribute]);
            }
        }
    }

    public static function helperFunctionSportFake(): void
    {
        $sports = PersianDataProvider::$sports;
        foreach ($sports as $sport) {
            if (Sport::query()->where('name', $sport)->doesntExist()) {
                Sport::query()->create(['name' => $sport]);
            }
        }
    }

    public static function helperFunctionGymFake(): void
    {
        $faker = FakerFactory::create();
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();
        $gyms = PersianDataProvider::$gyms;
        foreach ($gyms as $key => $gym_name) {
            if (Gym::query()->where('name', $gym_name)->doesntExist()) {
                /** @var Gym $gym */
                $is_ball = $faker->boolean;
                $gym = Gym::query()->create([
                    'name' => $gym_name,
                    'description' => $persianFaker->persianDescription(),
                    'price' => $faker->randomElement(range(700000, 1500000, 100000)),
                    'latitude' => $persianFaker->persianLatitude(),
                    'longitude' => $persianFaker->persianLongitude(),
                    'city_id' => City::query()->inRandomOrder()->first()->id,
                    'address' => $persianFaker->persianAddress(),
                    'short_address' => $persianFaker->persianُShortAddress(),
                    'score' => $faker->numberBetween(1, 5),
                    'status' => $faker->numberBetween(0, 2),
                    'profit_share_percentage' => $faker->numberBetween(1, 12),
                    'is_ball' => $is_ball,
                    'ball_price' => $is_ball ? $faker->randomElement([100000,150000, 200000]) : 0,
                    'gender_acceptance' => $faker->randomElement([ReserveTemplate::status_gender_acceptance_unknown, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_female, ReserveTemplate::status_gender_acceptance_all]),
                    'like_count' => $faker->numberBetween(15, 85),
                    'dislike_count' => $faker->numberBetween(10, 90),
                    'user_gym_manager_id' => $faker->randomElement([null, User::query()->inRandomOrder()->first()->id]),
                ]);

                // Attach random categories to the gym
                $categories = Category::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $gym->categories()->attach($categories);

                // Attach random tags to the gym
                $tags = Tag::query()->inRandomOrder()->limit(rand(1, 5))->get();
                $gym->tags()->attach($tags);

                // Attach random sports to the gym
                $sports = Sport::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $gym->sports()->attach($sports);

                // Attach random keywords to the gym
                $keywords = Keyword::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $gym->keywords()->attach($keywords);

                // Attach random attributes to the gym
                $attributes = Attribute::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $gym->attributes()->attach($attributes);

                self::helperFunctionReserveTemplatesFake(gym_id:$gym->id,is_ball:$is_ball);

                # set image
                $image_directory = public_path('fake_images/');
                $image_files = glob($image_directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                $random_images = $faker->randomElements($image_files, $faker->numberBetween(1, 3));
                self::helperFunctionSaveImage($gym, $random_images);
            }
        }
    }

    public static function helperFunctionReserveTemplatesFake(int $gym_id,$is_ball=false): void
    {
        $faker = \Faker\Factory::create();
        $days = $faker->randomElement([7, 5, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7]); // Randomly choose between 7, 5, or 6 (with a higher chance of 7)
        $interval = $faker->randomElement([5400, 7200]); // Randomly choose between 1.5 hours and 2 hours
        // Generate a single random price for all records

        // Loop through week_number from 1 to 7
        for ($week_number = 1; $week_number <= $days; $week_number++) {
            $price = $faker->randomElement(range(700000, 1500000, 100000));
            $user_random_id = User::query()->inRandomOrder()->first()->id;
            $from = $faker->randomElement(['06:00', '08:00', '10:00']); // Generate a new random start time for each day
            $current_hour = (int)substr($from, 0, 2); // Extract the hour part as an integer

            // Calculate the maximum allowed hour based on the desired end times
            $max_hour = (strtotime('22:00') < strtotime('24:00')) ? 22 : 24;

            // Continue generating records until it's near 22:00 or 24:00
            while ($current_hour < $max_hour) {
                $to = date('H:i', strtotime($from) + $interval);
                // Check if the end time exceeds the maximum allowed hour
                if (strtotime($to) >= strtotime('24:00') || strtotime($to) >= strtotime('22:00')) {
                    break; // Exit the loop if it's near 22:00 or 24:00
                }
                $is_ball_reserve_template = $is_ball ? $faker->boolean : false;
                ReserveTemplate::query()->create([
                    'from' => $from,
                    'to' => $to,
                    'gym_id' => $gym_id, // Use the gym_id passed as a parameter
                    'week_number' => $week_number, // Set the week_number
                    'price' => $price, // Use the same price for all records
                    'cod' => $faker->boolean,
                    'is_ball' => $is_ball_reserve_template,
                    'gender_acceptance' => $faker->randomElement([ReserveTemplate::status_gender_acceptance_unknown, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_female, ReserveTemplate::status_gender_acceptance_all]),
                    'discount' => $faker->randomElement([null, null, null, null, 5, 10, 15, 20]),
                    'status' => $faker->randomElement([
                        ReserveTemplate::status_active,
                        ReserveTemplate::status_active,
                        ReserveTemplate::status_active,
                        ReserveTemplate::status_active,
                        ReserveTemplate::status_active,
                        ReserveTemplate::status_inactive,
                    ]),
                    'user_creator' => $user_random_id,
                    'user_editor' => $user_random_id,
                ]);

                // Set the new 'from' time to 'to' time for the next iteration
                $from = $to;
                $current_hour = (int)substr($from, 0, 2); // Update the current hour
            }
        }
    }

    public static function helperFunctionReservesFake($reserve_template_id = null, $count = 400): void
    {
        $faker = FakerFactory::create();

        $count = $count ?? $faker->numberBetween(600, 800);

        for ($i = 0; $i < $count; $i++) {

            $user_creator_or_editor = User::query()->inRandomOrder()->first()->id;

            /** @var ReserveTemplate $reserve_template */
            $reserve_template = ReserveTemplate::query()->inRandomOrder()->first();

            # Generate random data for a reserve reservation.
            $georgian_date = $faker->dateTimeThisMonth();

            # Convert the Georgian date to a Carbon\Carbon object.
            $carbon_date = self::getRandomDateThisWeek();/* Carbon::instance($georgian_date); */

            # Format the Carbon date as 'Y-m-d' (date format)
            $formatted_georgian_date = $carbon_date->format('Y-m-d');

            # Convert the Carbon date to a Jalali (Persian) date and format it
            $jalali_date = Jalalian::fromCarbon($carbon_date);
            $formatted_jalali_date = $jalali_date->format('Y-m-d');

            # Check if a record with the same dated_at and reserve_template_id already exists
            $existing_record = Reserve::query()
                ->where('dated_at', $formatted_georgian_date)
                ->where('reserve_template_id', $reserve_template->id)
                ->first();

            // If no existing record found, create a new reserve reservation using the model
            if (!$existing_record) {
                $reserve_fake = [
                    'reserve_template_id' => $reserve_template->id,
                    'gym_id' => $reserve_template->gym_id,
                    'user_id' => User::query()->inRandomOrder()->first()->id,
                    'payment_status' => $faker->boolean,
                    'user_creator' => $user_creator_or_editor,
                    'user_editor' => $user_creator_or_editor,
                    'dated_at' => $formatted_georgian_date,
                    'status'=>Reserve::status_reserved,
                    'reserved_at' => $formatted_georgian_date, // You can customize this as needed
                    'reserved_user_id' => User::query()->inRandomOrder()->first()->id,
                ];

                # Create a new reserve reservation using the model
                /** @var Reserve $reserve */
                $reserve = Reserve::query()->create($reserve_fake);

                /** @var FactorService $factorService */
                $factorService = resolve('FactorService');

                /** @var Factor $factor */
                $factor = $factorService->store(['reserve_id' => $reserve->id, 'status' => Reserve::status_active]);

                $factor->update([
                    'description'=>Factor::calculateDescriptionReserves(collect([$reserve])),
                    'total_price'=>Factor::calculatePriceForFactorReserves(collect([$reserve]))
                ]);

                PaymentService::save_transactions($factor);
                PaymentService::fake_payment($factor);
            }
        }
    }

    private static function getRandomDateThisWeek(): Carbon
    {
        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();
        // Generate a random timestamp within the current week
        $randomTimestamp = mt_rand($startOfWeek->timestamp, $endOfWeek->timestamp);
        // Create a Carbon object from the random timestamp
        return Carbon::createFromTimestamp($randomTimestamp);
    }

    function helperFunctionUserFake($count = 60): void
    {
        $faker = \Faker\Factory::create('fa_IR');
        $role_user = RolesEnum::user->name;
        $role_gym_manager = RolesEnum::gym_manager->name;
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = app(PersianDataProvider::class);
        for ($i = 0; $i < $count; $i++) {
            /** @var User $user */
            $user = User::query()->create([
                'password' => Hash::make('123456789'),
                'username' => $faker->userName,
                'email' => $faker->unique()->email,
                'mobile' => $persianFaker->persianMobileUniqueNumber(),
                'status' => $faker->randomElement([
                    User::status_unknown,
                    User::status_active,
                    User::status_active,
                    User::status_active,
                    User::status_active,
                    User::status_active,
                    User::status_active,
                    User::status_active,
                    User::status_inactive,
                    User::status_block,
                ]),
                'mobile_verified_at' => now(),
                'email_verified_at' => now(),
            ]);
            $user->userDetail()->create([
                'name' => $persianFaker->persianName(),
                'family' => $faker->lastName,
                'father' => $faker->name,
                'national_code' => $faker->unique()->numerify('0#########'),
                'birthday' => $faker->dateTimeBetween('-35 years', '-6 years')->format('Y-m-d'),
                'gender' => $faker->randomElement([
                    UserDetail::gender_unknown,
                    UserDetail::gender_female,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                    UserDetail::gender_male,
                ]),
                'latitude' => $persianFaker->persianLatitude(),
                'longitude' => $persianFaker->persianLongitude(),
                'city_id' => City::query()->inRandomOrder()->first()->id,
                'address' => $persianFaker->persianAddress(),
            ]);

            # set fake role
            /** @var RoleService $roleService */
            $roleService = resolve('RoleService');
            $random_role = $faker->randomElement([$role_gym_manager, $role_user, $role_user, $role_user, $role_user, $role_user, $role_user]);
            $role_id = Role::query()->where('name', $random_role)->first()->id;
            $roleService->syncRoleToUser(['role_id' => $role_id, 'user_id' => $user->id]);
            $random_image = self::select_random_avatar_image();
            # save avatar
            self::helperFunctionSaveAvatar($user, $random_image);
        }
    }

    public static function select_random_avatar_image()
    {
        $faker = \Faker\Factory::create('fa_IR');
        $image_directory = public_path('faker_avatars/');
        $image_files = glob($image_directory . '*.{jpg,jpeg}', GLOB_BRACE);
        $random_image = $faker->randomElement($image_files);
        return $random_image;
    }

    public static function helperFunctionSaveSliderImage(Slider $slider): void
    {
        $faker = FakerFactory::create();

        $image_directory = public_path('fake_sliders/');
        $image_files = glob($image_directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $random_image = $faker->randomElement($image_files);
        if ($random_image) {
            $image = new UploadedFile($random_image, basename($random_image));
            $nameFile = ImageService::setNameFile($image);
            $pathImage = $image->storeAs('sliders', $nameFile);
            if ($pathImage) {
                $slider->update(['image' => $pathImage]);
            }
        }
    }

    public static function helperFunctionSliderFake($count = 15): void
    {
        $faker = FakerFactory::create();

        // # set image

        for ($i = 0; $i < $count; $i++) {
            $user_random_id = User::query()->inRandomOrder()->first()->id;

            /** @var Slider $slider */
            $slider = Slider::query()->create([
                'title' => PersianDataProvider::sliderTitleUnique(),
                'text' => PersianDataProvider::sliderTextUnique(),
                'link' => $faker->randomElement([$faker->url(), null]),
                'status' => $faker->randomElement([Slider::status_unknown, Slider::status_active, Slider::status_active, Slider::status_active, Slider::status_active, Slider::status_inactive]),
                'city_id' => City::query()->inRandomOrder()->first()->id || null,
                'user_creator' => $user_random_id,
                'user_editor' => $user_random_id,
            ]);

            self::helperFunctionSaveSliderImage($slider);
        }
    }

    public static function helperFunctionFactorFake(int $number_record = 70): void
    {
        $faker = FakerFactory::create();
        for ($i = 0; $i < $number_record; $i++) {
            $user_id_random = User::query()->inRandomOrder()->first()->id;

            /** @var Reserve $reserve */
            $reserve = Reserve::query()->inRandomOrder()->first();

            /** @var Factor $factor */
            $factor = Factor::query()->create([
                'total_price' => 0,
                'status' => $faker->randomElement([Factor::status_paid, Factor::status_unpaid, Factor::status_unknown]),
                'user_id' => $user_id_random,
                'gym_id' => $reserve->gym_id,
            ]);

            $factor->reserves()->attach($reserve->id, ['price' => $reserve->reserveTemplate->price]);
        }
    }

    public static function helperFunctionFakePayment($number = 80): void
    {
        $faker = FakerFactory::create();
        $allowableStatuses = Payment::getStatusPayment();
        for ($i = 0; $i < $number; $i++) {
            $user_id = User::query()->inRandomOrder()->first()->id;
            $factor_id = Factor::query()->inRandomOrder()->first()->id;
            Payment::query()->create([
                'status' => $faker->randomElement($allowableStatuses),
                'resnumber' => Payment::resnumberUnique(),
                'amount' => rand(800000, 1800000),
                'factor_id' => $factor_id,
                'user_id' => $user_id,
                'user_creator' => $user_id,
                'user_editor' => $user_id,
            ]);
        }
    }

    public static function deleteImages(): void
    {
        Artisan::call('gym:delete-images', ['--all' => true]);
    }

    public static function helperFunctionFakeAttributeGymPrice($count = 40): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < $count; $i++) {
            $attributeId = Attribute::query()->inRandomOrder()->first()->id;
            $gymId = Gym::query()->inRandomOrder()->first()->id;

            AttributePrice::query()->firstOrCreate([
                'attribute_id' => $attributeId,
                'gym_id' => $gymId,
                'price' => $faker->numberBetween(200000, 300000),
            ]);
        }
    }

    public static function helperFunctionFakeAttributeGymPriceReserve($count = 100): void
    {
        $faker = \Faker\Factory::create();
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $attributePriceId = AttributePrice::query()->inRandomOrder()->first()->id;
            $reserveId = Reserve::query()->inRandomOrder()->first()->id;

            // Check if the combination of attribute_gym_price_id and reserve_id already exists in $data
            $uniqueCheck = collect($data)->where('attribute_gym_price_id', $attributePriceId)
                ->where('reserve_id', $reserveId)
                ->isEmpty();

            if ($uniqueCheck) {
                $data[] = [
                    'attribute_gym_price_id' => $attributePriceId,
                    'reserve_id' => $reserveId,
                ];
            }
        }

        try {
            DB::table('attribute_gym_price_reserve')->insertOrIgnore($data);
        } catch (\Exception $exception) {
            // Log or handle the exception if needed
        }
    }

    public function run(): void
    {
        self::deleteImages();
        self::helperFunctionUserFake();
        self::helperFunctionKeywordFake();
        self::helperFunctionTagFake();
        self::helperFunctionCategoryFake();
        self::helperFunctionAttributeFake();
        self::helperFunctionSportFake();
        self::helperFunctionGymFake();
        self::helperFunctionReservesFake();
        self::helperFunctionSliderFake();
//        self::helperFunctionFactorFake();
//        self::helperFunctionFakePayment();
        self::helperFunctionCommonComplaintFake();
        self::helperFunctionComplaintFake();
        // self::helperFunctionFakeAttributeGymPrice();
        // self::helperFunctionFakeAttributeGymPriceReserve();
    }

}
