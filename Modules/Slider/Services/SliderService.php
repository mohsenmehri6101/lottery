<?php

namespace Modules\Slider\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Gym\Services\ImageService;
use Modules\Slider\Entities\Slider;
use Modules\Slider\Http\Repositories\SliderRepository;
use Modules\Slider\Http\Requests\Slider\SliderIndexRequest;
use Modules\Slider\Http\Requests\Slider\SliderShowRequest;
use Modules\Slider\Http\Requests\Slider\SliderStoreRequest;
use Modules\Slider\Http\Requests\Slider\SliderUpdateRequest;

class SliderService
{
    public function __construct(public SliderRepository $sliderRepository)
    {
    }

    public function index(SliderIndexRequest $request)
    {
        try {
            $fields = $request->validated();
            $relations = $fields['withs'] ?? ['image'];

            return $this->sliderRepository->resolve_paginate(inputs: $fields, relations: $relations);

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function show(SliderShowRequest $request, $slider_id)
    {
        try {
            $fields = $request->validated();

            /**
             * @var $withs
             */
            extract($fields);

            $relations = $withs ?? [];
            return $this->sliderRepository->withRelations(relations: $relations)->findOrFail($slider_id);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function store(SliderStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $title
             * @var $image
             * @var $link
             * @var $status
             * @var $city_id
             */
            extract($fields);


            # save Slider
            $fields_slider = [
                'title' => $title ?? null,
                'link' => $link ?? null,
                'status' => $status ?? Slider::status_unknown,
                'city_id' => $city_id ?? null,
            ];

            $image=$image[0];
            # save slider
            $image = $image ?? null;
            if ($image) {
                # delete slider before
                $name_file = ImageService::setNameFile($image);
                // todo convert image change image size and with and height
                $path_slider = $image->storeAs('sliders', $name_file);
                if ($path_slider) {
                    $fields_slider['image'] = $path_slider;
                }
            }

            /** @var Slider $slider */
            $slider = $this->sliderRepository->create($fields_slider);

            DB::commit();

            $slider_id = $slider?->id;

            return $this->sliderRepository->findOrFail($slider_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function update(SliderUpdateRequest $request, $slider_id)
    {
        DB::beginTransaction();
        try {
            $fields = $request->validated();

            /**
             * @var $title
             * @var $image
             * @var $link
             * @var $image
             * @var $status
             * @var $city_id
             */
            extract($fields);

            /** @var Slider $slider */
            $slider = $this->sliderRepository->findOrFail($slider_id);

            # update slider
            $fields_slider = [
                'title' => $title ?? $slider->title ?? null,
                'link' => $link ?? $slider->link ?? null,
                'status' => $status ?? $slider->status ?? null,
                'city_id' => $city_id ?? $slider->city_id ?? null,
            ];

            # update image
            $image = $image ?? null;
            if ($image) {
                # delete image before
                $name_file = ImageService::setNameFile($image);
                // todo convert image change image size and with and heigh
                $path_image = $image->storeAs('sliders', $name_file);
                if ($path_image) {
                    helperDeleteFiles($slider->image);
                    $fields_slider['image'] = $path_image;
                }
            }

            $this->sliderRepository->update($slider, $fields_slider);

            DB::commit();

            return $this->sliderRepository->findOrFail($slider_id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function destroy($slider_id): bool
    {
        DB::beginTransaction();
        try {
            # find Slider
            /** @var Slider $slider */
            $slider = $this->sliderRepository->findOrFail($slider_id);

            # delete images
            helperDeleteFiles($slider->image);

            $this->sliderRepository->forceDelete($slider);

            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function listStatusSlider($status = null): array|bool|int|string|null
    {
        return Slider::getStatusSliderTitle($status);
    }

}
