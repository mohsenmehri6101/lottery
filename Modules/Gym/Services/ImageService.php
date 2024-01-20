<?php

namespace Modules\Gym\Services;

use App\Exceptions\Contracts\DeveloperException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//use Intervention\Image\ImageManager as Image;
//use Intervention\Image\Facades\Image;
use Exception;

class ImageService
{
    use  HelpersFileTrait;
    const default_is_cover = false;
    const default_is_public = false;
    const default_is_water_mark = false;

    # main function
    public static function saveImage(
        $image,
        Model $model,
        string $title = null,
        bool $is_cover = self::default_is_cover,
        bool $is_public = self::default_is_public,
        bool $is_water_mark = self::default_is_water_mark,
        $destinationPath = null,
        $relation = null
    )
    {
        DB::beginTransaction();
        try {
            if ($image instanceof Request) {
                $image = $image?->get('image') ?? $image?->get('file') ?? null;
            }

            $img_ext = null;
            $image_webp = config_(key:'configs.images.image_webp',default: true,title: 'عکس ها فشرده سازی و تبدیل به فرمت webp شوند؟');
            if ($image_webp) {
                #convert image to webp format
                $img_ext = "webp";

                $imageEncoded = Image::make($image->getRealPath())->encode(format: $img_ext, quality: 40)->save($image->getRealPath());
                $image = new UploadedFile($imageEncoded->basePath(), $imageEncoded->filename);
            }

            $client_original_extension = self::getClientOriginalExtension($image);
            $original_name_image = self::getClientOriginalName($image);
            $new_name_image = $img_ext ? self::setNameFile(file: $image) . $img_ext : self::setNameFile(file: $image);
            $destinationPath = self::destinationPath($destinationPath);
            $image_url = Storage::putFileAs(path: $destinationPath, file: $image, name: $new_name_image);

            if (!$image_url) {
                throw new Exception(trans("custom.defaults.upload_failed"));
            }
            $image_url = str_replace('public', 'storage', $image_url);

            # save image model
            $data = [
                'title' => $title ?? null,
                'original_name' => $original_name_image,
                'image' => $new_name_image,
                'type' => $client_original_extension,
                'url' => $image_url,
                'is_cover' => $is_cover,
                'is_public' => $is_public,
                'is_water_mark' => $is_water_mark,
            ];

            if ($relation && method_exists($model, $relation)) {
                $result = $model->$relation()->create($data);
            } elseif (method_exists($model, 'image')) {
                $result = $model->image()->create($data);
            } elseif (method_exists($model, 'images')) {
                $result = $model->images()->create($data);
            } else {
                throw new DeveloperException(message: 'function image or images not set in model');
            }

            DB::commit();

            return $result;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private static function destinationPath($destinationPath = null)
    {
        if (is_null($destinationPath) || !filled($destinationPath)) {
            return config_('configs.images.destination_path_default');
        }
        return $destinationPath;
    }

    private static function getClientOriginalName($file)
    {
        return $file?->getClientOriginalName() ?? null;
    }

    private static function getClientOriginalExtension($file)
    {
        return $file?->getClientOriginalExtension() ?? null;
    }

    public static function setNameFile($file, $type = null, $length = 2, $start_with = null, $end_with = null): string
    {
        $start_with = $start_with ?? $type;
        $end_with = $end_with ?? time();
        $end_with = $end_with . "." . ($file?->getClientOriginalExtension() ?? 'webp');
        return random_string(length: $length, start_with: $start_with, end_with: $end_with);
    }

    public static function deleteImages(Model $model, $relation = null, $strict = true): bool
    {
        DB::beginTransaction();
        try {
            if (is_null($relation)) {
                if (method_exists($model, 'image')) {
                    $relation = 'image';
                } elseif (method_exists($model, 'images')) {
                    $relation = 'images';
                }
            }

            $links = $model?->$relation?->pluck('url') ?? [];
            if ($strict && count($links) > 0) {
                /* حذف فیزیکی عکس*/
                self::helperDeleteFiles($links);
            }
            /* حذف عکس از دیتابیس*/
            $model->$relation()->delete();
            DB::commit();
            return true;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
