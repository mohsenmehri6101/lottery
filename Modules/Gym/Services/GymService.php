<?php

namespace Modules\Gym\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Entities\ReserveTemplate;
use Modules\Gym\Entities\Gym;
use Modules\Gym\Entities\Image;
use Modules\Gym\Http\Repositories\GymRepository;
use Modules\Gym\Http\Requests\Gym\DeleteImageGymRequest;
use Modules\Gym\Http\Requests\Gym\GymIndexRequest;
use Modules\Gym\Http\Requests\Gym\GymLikeRequest;
use Modules\Gym\Http\Requests\Gym\GymShowRequest;
use Modules\Gym\Http\Requests\Gym\GymStoreRequest;
use Modules\Gym\Http\Requests\Gym\GymUpdateRequest;
use Modules\Gym\Http\Requests\Gym\MyGymsRequest;
use Modules\Gym\Http\Requests\Gym\GetInitializeRequestsSelectors;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class GymService
{
    public function __construct(public GymRepository $gymRepository)
    {
    }
    public function index(GymIndexRequest|array $request)
    {
        try {
            if (is_array($request)) {
                $gymIndexRequest = new GymIndexRequest();
                $fields = Validator::make(data: $request,
                    rules: $gymIndexRequest->rules(),
                    attributes: $gymIndexRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }
            /**
             * @var $max_price
             * @var $min_price
             */
            extract($fields);

            $max_price = $max_price ?? null;
            $min_price = $min_price ?? null;

            $query = $this->gymRepository->queryFull(inputs: $fields);

            $query = $query->when($max_price, function ($query_) use ($max_price) {
                return $query_->where('price', '<=', $max_price);
            })->when($min_price, function ($query_) use ($min_price) {
                return $query_->where('price', '>=', $min_price);
            });
            return $this->gymRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function myGyms(MyGymsRequest|array $request)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $max_price
             * @var $min_price
             */
            extract($fields);

            $max_price = $max_price ?? null;
            $min_price = $min_price ?? null;

            $user_id = get_user_id_login();

            $fields = [...$fields, 'user_id' => $user_id];

            $query = $this->gymRepository->queryFull(inputs: $fields);

            $query = $query->whereNotNull('user_id');

            $query = $query
                ->when($max_price, function ($query_) use ($max_price) {
                    return $query_->where('price', '<=', $max_price);
                })
                ->when($min_price, function ($query_) use ($min_price) {
                    return $query_->where('price', '>=', $min_price);
                });
            return $this->gymRepository->resolve_paginate(query: $query);

        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function show(GymShowRequest|array $request, $gym_id)
    {
        try {
            if (is_array($request)) {
                $userStoreRequest = new GymShowRequest();
                $fields = Validator::make(data: $request,
                    rules: $userStoreRequest->rules(),
                    attributes: $userStoreRequest->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            $withs = convert_withs_from_string_to_array(withs: $withs);
            return $this->gymRepository->withRelations(relations: $withs)->findOrFail($gym_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function store(GymStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $status
             * @var $tag_id
             * @var $tags
             * @var $category_id
             * @var $categories
             * @var $keyword_id
             * @var $keywords
             * @var $sport_id
             * @var $sports
             * @var $attribute_id
             * @var $attributes
             * @var $images
             * @var $price
             * @var $profit_share_percentage
             * @var $gender_acceptance
             * @var $time_template
             */
            extract($fields);
            unset(
                $fields['time_template'],
                $fields['tag_id'],
                $fields['tags'],
                $fields['keyword_id'],
                $fields['keywords'],
                $fields['sport_id'],
                $fields['sports'],
                $fields['attribute_id'],
                $fields['attributes'],
                $fields['category_id'],
                $fields['categories'],
            );

            $withs_result = [];

            /** @var Gym $gym */
            $gym = $this->gymRepository->create($fields);

            # set tags
            $tag_id = $tag_id ?? null;
            $tags = $tags ?? [];
            if (isset($tag_id) && $tag_id) {
                $tags[] = $tag_id;
                $tags = array_unique($tags);
            }
            if (count($tags) > 0) {
                /** @var TagService $tagService */
                $tagService = resolve('TagService');
                $tagService->syncTagToGym(['gym_id' => $gym->id, 'tags' => $tags]);
                $withs_result[] = 'tags';
            }

            # set sport
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];
            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }
            if (count($sports) > 0) {
                /** @var SportService $sportService */
                $sportService = resolve('SportService');
                $sportService->syncSportToGym(['gym_id' => $gym->id, 'sports' => $sports]);
                $withs_result[] = 'sports';
            }

            # set attribute
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];
            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }
            if (count($attributes) > 0) {
                /** @var AttributeService $attributeService */
                $attributeService = resolve('AttributeService');
                $attributeService->syncAttributeToGym(['gym_id' => $gym->id, 'attributes' => $attributes]);
                $withs_result[] = 'attributes';
            }

            # set keyword
            $keyword_id = $keyword_id ?? null;
            $keywords = $keywords ?? [];
            if (isset($keyword_id) && $keyword_id) {
                $keywords[] = $keyword_id;
                $keywords = array_unique($keywords);
            }
            if (count($keywords) > 0) {
                /** @var KeywordService $keywordService */
                $keywordService = resolve('KeywordService');
                $keywordService->syncKeywordToGym(['gym_id' => $gym->id, 'keywords' => $keywords]);
                $withs_result[] = 'keywords';
            }

            # set categories
            $category_id = $category_id ?? null;
            $categories = $categories ?? [];
            if (isset($category_id) && $category_id) {
                $categories[] = $category_id;
                $categories = array_unique($categories);
            }
            if (count($categories) > 0) {
                /** @var CategoryService $categoryService */
                $categoryService = resolve('CategoryService');
                $categoryService->syncCategoryToGym(['gym_id' => $gym->id, 'categories' => $categories]);
                $withs_result[] = 'categories';
            }

            # save images
            $images = $images ?? [];
            if (count($images)) {
                foreach ($images as $image) {
                    if ($image) {
                        # delete avatar before
                        $name_file = ImageService::setNameFile($image);
                        // todo convert image change image size and with and height
                        $path_image = $image->storeAs('gyms', $name_file);
                        if ($path_image) {
                            # $images_links[] = ['url' => $path_image, 'image' => $name_file];
                            $imageModel = new Image(['url' => $path_image, 'image' => $name_file]);
                            $gym->images()->save($imageModel);
                        }
                    }
                }
                $withs_result[] = 'urlImages';
            }

            # save reserve_template
            if (isset($time_template) && count($time_template)) {
                $from = $time_template['from'] ?? '08:00';
                $to = $time_template['to'] ?? '23:59';
                $break_time = $time_template['break_time'] ?? 2;
                $price = $time_template['price'] ?? 0;
                $gender_acceptance = $time_template['gender_acceptance'] ?? ReserveTemplate::status_gender_acceptance_unknown;
                $week_numbers = $time_template['week_numbers'] ?? [1, 2, 3, 4, 5, 6, 7];
                self::saveSectionReserveTemplate(gym: $gym, week_numbers: $week_numbers, start_time: $from, max_hour: $to, break_time: $break_time, price: $price, gender_acceptance: $gender_acceptance);
                $withs_result[] = 'reserveTemplates';
            }

            DB::commit();
            return $this->gymRepository->withRelations(relations: $withs_result)->findOrFail($gym->id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public static function saveSectionReserveTemplate(Gym $gym, $week_numbers = [1, 2, 3, 4, 5, 6, 7], $start_time = '08:00', $max_hour = '23:59', $break_time = 2, $price = 0, $gender_acceptance = ReserveTemplate::status_gender_acceptance_unknown): void
    {
        foreach ($week_numbers as $week_number) {
            $from = $start_time;
            while (strtotime($from) + ($break_time * 3600) <= strtotime("$max_hour:00")) {
                $to = date('H:i', strtotime($from) + ($break_time * 3600));
                if (strtotime($to) > strtotime("$max_hour:00")) {
                    break;
                }
                $to = $to == '23:59' ? '24:00' : $to;
                ReserveTemplate::query()->create([
                    'from' => $from,
                    'to' => $to,
                    'gym_id' => $gym->id,
                    'week_number' => $week_number,
                    'price' => $price,
                    'gender_acceptance' => $gender_acceptance ?? ReserveTemplate::status_gender_acceptance_unknown,
                ]);
                $from = date('H:i', strtotime($from) + ($break_time * 3600));
            }
        }
    }
    public function like(GymLikeRequest $request): int
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var integer $gym_id
             * @var string $type
             */
            extract($fields);

            $gym_id = $gym_id ?? null;
            $type = $type ?? null;
            $likes_count = Gym::like(gym_id: $gym_id, type: $type);

            DB::commit();
            return $likes_count;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function update(GymUpdateRequest $request, $gym_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $status
             * @var $tag_id
             * @var $tags
             * @var $category_id
             * @var $categories
             * @var $keyword_id
             * @var $keywords
             * @var $sport_id
             * @var $sports
             * @var $attribute_id
             * @var $attributes
             * @var $images
             */
            extract($fields);

            unset(
                $fields['tag_id'],
                $fields['tags'],
                $fields['keyword_id'],
                $fields['keywords'],
                $fields['sport_id'],
                $fields['sports'],
                $fields['attribute_id'],
                $fields['attributes'],
                $fields['category_id'],
                $fields['categories'],
            );

            /** @var Gym $gym */
            $gym = $this->gymRepository->findOrFail($gym_id);

            $this->gymRepository->update($gym, $fields);

            # set tags
            $tag_id = $tag_id ?? null;
            $tags = $tags ?? [];
            $tag_detach = $tag_detach ?? false;
            if (isset($tag_id) && $tag_id) {
                $tags[] = $tag_id;
                $tags = array_unique($tags);
            }
            if (count($tags) > 0) {
                /** @var TagService $tagService */
                $tagService = resolve('TagService');
                $tagService->syncTagToGym(['gym_id' => $gym->id, 'tags' => $tags, 'detach' => $tag_detach]);
            }
            # set categories
            $category_id = $category_id ?? null;
            $categories = $categories ?? [];
            $category_detach = $category_detach ?? false;
            if (isset($category_id) && $category_id) {
                $categories[] = $category_id;
                $categories = array_unique($categories);
            }
            if (count($categories) > 0) {
                /** @var CategoryService $categoryService */
                $categoryService = resolve('CategoryService');
                $categoryService->syncCategoryToGym(['gym_id' => $gym->id, 'categories' => $categories, 'detach' => $category_detach]);
            }

            # set sport
            $sport_id = $sport_id ?? null;
            $sports = $sports ?? [];
            if (isset($sport_id) && $sport_id) {
                $sports[] = $sport_id;
                $sports = array_unique($sports);
            }
            if (count($sports) > 0) {
                /** @var SportService $sportService */
                $sportService = resolve('SportService');
                $sportService->syncSportToGym(['gym_id' => $gym->id, 'sports' => $sports]);
                $withs_result[] = 'sports';
            }

            # set attribute
            $attribute_id = $attribute_id ?? null;
            $attributes = $attributes ?? [];
            if (isset($attribute_id) && $attribute_id) {
                $attributes[] = $attribute_id;
                $attributes = array_unique($attributes);
            }
            if (count($attributes) > 0) {
                /** @var AttributeService $attributeService */
                $attributeService = resolve('AttributeService');
                $attributeService->syncAttributeToGym(['gym_id' => $gym->id, 'attributes' => $attributes]);
                $withs_result[] = 'attributes';
            }

            # set keyword
            $keyword_id = $keyword_id ?? null;
            $keywords = $keywords ?? [];
            if (isset($keyword_id) && $keyword_id) {
                $keywords[] = $keyword_id;
                $keywords = array_unique($keywords);
            }
            if (count($keywords) > 0) {
                /** @var KeywordService $keywordService */
                $keywordService = resolve('KeywordService');
                $keywordService->syncKeywordToGym(['gym_id' => $gym->id, 'keywords' => $keywords]);
                $withs_result[] = 'keywords';
            }


            # save images
            $images = $images ?? [];

            if (count($images)) {
                foreach ($images as $image) {
                    if ($image) {
                        # delete avatar before
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

            DB::commit();

            return $this->gymRepository->findOrFail($gym_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function destroy($gym_id)
    {
        DB::beginTransaction();
        try {
            # find gym
            /** @var Gym $gym */
            $gym = $this->gymRepository->findOrFail($gym_id);

            # delete gym
            $status_delete_gym = $this->gymRepository->delete($gym);

            DB::commit();
            return $status_delete_gym;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function deleteImage(DeleteImageGymRequest $request, $gym_id): bool
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $image_id
             * @var $images
             */
            extract($fields);

            $image_id = $image_id ?? null;
            $images = $images ?? [];

            if (isset($image_id) && $image_id) {
                $images[] = $image_id;
                $images = array_unique($images);
            }

            # find gym
            /** @var Gym $gym */
            $gym = $this->gymRepository->findOrFail($gym_id);

            # delete images from gym
            $image_query = $gym->images()->whereIn('images_gyms.id', $images);
            $images_db = $image_query->get();
            if (count($images_db)) {
                $images_db->each(function (Image $image) {
                    $url = $image->url ?? null;
                    helperDeleteFiles($url);
                });
            }

            $image_query->delete();

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public static function updateScore($gym_id): float|int
    {
        return Gym::updateScore($gym_id);
    }
    public function gymStatus(Request $request): array|bool|int|string|null
    {
        $status = $request->status ?? null;
        return Gym::getStatusGymTitle();
    }
    public function getInitializeRequestsSelectors(GetInitializeRequestsSelectors|array $request): array
    {
        try {
            if (is_array($request)) {
                $get_initialize_requests_selectors = new GetInitializeRequestsSelectors();
                $fields = Validator::make(data: $request,
                    rules: $get_initialize_requests_selectors->rules(),
                    attributes: $get_initialize_requests_selectors->attributes(),
                )->validate();
            } else {
                $fields = $request->validated();
            }

            /**
             * @var $withs
             */
            extract($fields);
            $withs = $withs ?? [];
            $withs = array_values($withs);
            $lists = [];
            foreach ($withs as $with) {
                $cacheKey = "initialize_requests_selectors_$with";
                // Check if the data is available in the cache
                if (Cache::has($cacheKey)) {
                    $lists[$with] = Cache::get($cacheKey);
                } else {
                    switch ($with) {
                        case 'gyms':
                            $lists[$with] = $this->getCachedList('GymService', 'index', 'gyms');
                            break;
                        case 'tags':
                            $lists[$with] = $this->getCachedList('TagService', 'index', 'tags');
                            break;
                        case 'sports':
                            $lists[$with] = $this->getCachedList('SportService', 'index', 'sports');
                            break;
                        case 'keywords':
                            $lists[$with] = $this->getCachedList('KeywordService', 'index', 'keywords');
                            break;
                        case 'attributes':
                            $lists[$with] = $this->getCachedList('AttributeService', 'index', 'attributes');
                            break;
                        case 'cities':
                            $lists[$with] = $this->getCachedList('CityService', 'index', 'cities');
                            break;
                        case 'provinces':
                            $lists[$with] = $this->getCachedList('ProvinceService', 'index', 'provinces');
                            break;
                        case 'categories':
                            $lists[$with] = $this->getCachedList('CategoryService', 'index', 'categories');
                            break;
                        case 'gender_acceptances':
                            $lists[$with] = $this->getCachedList('ReserveTemplateService', 'gender_acceptances', 'gender_acceptances');
                            break;
                        default:
                            // Handle unknown $with values or log a warning
                            break;
                    }
                    // Set data in cache for 30 minutes
                    Cache::put($cacheKey, $lists[$with], now()->addMinutes(30));
                }
            }
            return $lists;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    private function getCachedList($serviceKey, $method, $cacheKey)
    {
        $minute_cache_time = config('configs.gyms.cache_time_initialize_requests_selectors', 30);
        $service = resolve($serviceKey);
        $data = $service->$method([])->toArray()['data'];
        return Cache::remember("initialize_requests_selectors_$cacheKey", now()->addMinutes($minute_cache_time), function () use ($data) {
            return $data;
        });
    }

}
