<?php

if (!function_exists('helperDeleteFiles')) {
    function helperDeleteFiles($links = null): ?bool
    {
        if (is_null($links)) {
            return null;
        }
        if (is_string($links)) {
            $links = [$links];
        } elseif ($links instanceof \Illuminate\Support\Collection) {
            $links = $links?->toArray() ?? [];
        }
        foreach ($links as $link) {
            try {
                # Path to the file.
                $filename = \Illuminate\Support\Facades\Storage::path($link);
                unlink(filename: $filename);
            } catch (\Exception $exception) {
                report($exception);
            }
        }
        return true;
    }
}
if (!function_exists('deleteReservedWithStatusReserving')) {
    function deleteReservedWithStatusReserving(): bool
    {
        /** @var Modules\Gym\Entities\Reserve $reserves_with_status_status_reserving */
        $reserves_with_status_status_reserving = Modules\Gym\Entities\Reserve::query()
            ->where('status',Modules\Gym\Entities\Reserve::status_reserving)
            ->where('created_at', '<=', Carbon\Carbon::now()->subMinutes(10))
            ->forceDelete();
        return true;
    }
}
