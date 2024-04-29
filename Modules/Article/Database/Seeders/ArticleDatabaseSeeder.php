<?php

namespace Modules\Article\Database\Seeders;

use App\Permissions\RolesEnum;
use App\Providers\Customize\PersianDataProvider;
use App\Providers\Customize\PersianFakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Entities\User;
use Modules\Authentication\Entities\UserDetail;
use Modules\Authorization\Entities\Role;
use Modules\Authorization\Services\RoleService;
use Modules\Geographical\Entities\City;
use Illuminate\Http\UploadedFile;
use Modules\Article\Entities\Attribute;
use Modules\Article\Entities\Category;
use Modules\Article\Entities\CommonComplaint;
use Modules\Article\Entities\Complaint;
use Modules\Article\Entities\Reserve;
use Modules\Article\Entities\ReserveTemplate;
use Modules\Article\Entities\Article;
use Modules\Article\Entities\Image;
use Modules\Article\Entities\Keyword;
use Modules\Article\Entities\Tag;
use Faker\Factory as FakerFactory;
use Modules\Article\Services\ArticleService;
use Modules\Article\Services\ImageService;
use Modules\Payment\Entities\Factor;
use Modules\Slider\Entities\Slider;
use Carbon\Carbon;

class ArticleDatabaseSeeder extends Seeder
{
    public static function helperFunctionSaveImage(Article $article, $images = []): void
    {
        if (count($images)) {
            foreach ($images as $image) {
                if ($image) {
                    $image = new UploadedFile($image, basename($image));
                    # delete  before
                    $name_file = ImageService::setNameFile($image);
                    // todo convert image change image size and with and height
                    $path_image = $image->storeAs('articles_images', $name_file);
                    if ($path_image) {
                        $imageModel = new Image(['url' => $path_image, 'image' => $name_file]);
                        $article->images()->save($imageModel);
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
                'article_id' => Article::query()->inRandomOrder()->first()->id,
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

    public static function helperFunctionArticleFake(): void
    {
        $faker = FakerFactory::create();
        /** @var PersianDataProvider $persianFaker */
        $persianFaker = PersianFakerFactory::create();
        $articles = PersianDataProvider::$articles;
        foreach ($articles as $key => $article_name) {
            if (Article::query()->where('name', $article_name)->doesntExist()) {
                /** @var Article $article */
                $is_ball = $faker->boolean;
                $article = Article::query()->create([
                    'name' => $article_name,
                    'description' => $persianFaker->persianDescription(),
                    'price' => $faker->randomElement(range(700000, 1500000, 100000)),
                    'latitude' => $persianFaker->persianLatitude(),
                    'longitude' => $persianFaker->persianLongitude(),
                    'city_id' => City::query()->inRandomOrder()->first()->id,
                    'address' => $persianFaker->persianAddress(),
                    'short_address' => $persianFaker->persianُShortAddress(),
                    'score' => $faker->numberBetween(1, 5),
                    'status' => $faker->randomElement([Article::status_disable,Article::status_active,Article::status_active,Article::status_active,Article::status_active]),
                    'profit_share_percentage' => $faker->numberBetween(1, 12),
                    'is_ball' => $is_ball,
                    'ball_price' => $is_ball ? $faker->randomElement([100000, 150000, 200000]) : 0,
                    'gender_acceptance' => $faker->randomElement([ReserveTemplate::status_gender_acceptance_unknown, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_male, ReserveTemplate::status_gender_acceptance_female, ReserveTemplate::status_gender_acceptance_all]),
                    'like_count' => $faker->numberBetween(15, 85),
                    'dislike_count' => $faker->numberBetween(10, 90),
                    'user_article_manager_id' => $faker->randomElement([null, User::query()->inRandomOrder()->first()->id]),
                ]);

                // Attach random categories to the article
                $categories = Category::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $article->categories()->attach($categories);

                // Attach random tags to the article
                $tags = Tag::query()->inRandomOrder()->limit(rand(1, 5))->get();
                $article->tags()->attach($tags);

                // Attach random keywords to the article
                $keywords = Keyword::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $article->keywords()->attach($keywords);

                // Attach random attributes to the article
                $attributes = Attribute::query()->inRandomOrder()->limit(rand(1, 3))->get();
                $article->attributes()->attach($attributes);

                # set image
                $image_directory = public_path('fake_images/');
                $image_files = glob($image_directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                $random_images = $faker->randomElements($image_files, $faker->numberBetween(1, 3));
                self::helperFunctionSaveImage($article, $random_images);

                # save reserve_templates
                self::helperFunctionReserveTemplatesFake($article);
            }
        }
    }

    public static function helperFunctionReserveTemplatesFake(Article $article, $is_ball = false): void
    {
        $faker = \Faker\Factory::create();
        $from = $faker->randomElement(['06:00', '07:00', '07:30', '08:00', '08:30', '10:00']);
        $to = $faker->randomElement(['22:00', '22:30', '24:00', '21:00']);
        $gender_acceptance = $faker->randomElement([
            ReserveTemplate::status_gender_acceptance_unknown,
            ReserveTemplate::status_gender_acceptance_male,
            ReserveTemplate::status_gender_acceptance_female,
            ReserveTemplate::status_gender_acceptance_all,
        ]);
        $randomWeeks = $faker->randomElement([
            [1, 2, 3, 4, 5, 6, 7],
            [1, 2, 3, 4, 5, 6, 7],
            [1, 2, 3, 4, 5, 6, 7],
            [3, 4, 5, 6, 7],
            [1, 2, 3, 4, 5, 6],
            [1, 2, 3, 4, 5, 6],
            [1, 2, 3, 4, 5, 6, 7],
            [2, 3, 4, 5, 6, 7],
        ]);
        $break_time = $faker->randomElement([1.5,2,2]);
        // Call saveSectionReserveTemplate method to save the reserve template
        ArticleService::saveSectionReserveTemplate(
            article: $article,
            week_numbers: $randomWeeks,
            start_time: $from,
            max_hour: $to,
            break_time: $break_time,
            gender_acceptance: $gender_acceptance,
        );
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
        $role_article_manager = RolesEnum::article_manager->name;
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
            $random_role = $faker->randomElement([$role_article_manager, $role_user, $role_user, $role_user, $role_user, $role_user, $role_user]);
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

    public static function deleteImages(): void
    {
        Artisan::call('article:delete-images', ['--all' => true]);
    }

    public function run(): void
    {
        self::deleteImages();
        self::helperFunctionUserFake();
        self::helperFunctionKeywordFake();
        self::helperFunctionTagFake();
        self::helperFunctionCategoryFake();
        self::helperFunctionAttributeFake();
        self::helperFunctionArticleFake();
        self::helperFunctionSliderFake();
        self::helperFunctionCommonComplaintFake();
        self::helperFunctionComplaintFake();
    }

}
