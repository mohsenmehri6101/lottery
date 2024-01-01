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
