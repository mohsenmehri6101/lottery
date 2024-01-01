<?php
namespace Modules\Gym\Console;

use Illuminate\Console\Command;
class ImageFakeDeleteCommand extends Command
{
    protected $signature = 'gym:delete-images {--all : Delete all images} {--sliders : Delete sliders images} {--slider : Delete sliders images} {--avatars : Delete avatars images} {--avatar : Delete avatars images} {--gyms : Delete gyms images} {--gym : Delete gyms images}';
    protected $description = 'Delete fake images';

    protected string $publicPath;

    public function __construct()
    {
        parent::__construct();
        $this->publicPath = $this->getPublicPath();
    }
    public function handle(): void
    {
        if ($this->option('all')) {
            $this->deleteAllImages();
        } elseif ($this->option('sliders') || $this->option('slider')) {
            $this->deleteSlidersImages();
        } elseif ($this->option('avatars') || $this->option('avatar')) {
            $this->deleteAvatarsImages();
        } elseif ($this->option('gyms') || $this->option('gym')) {
            $this->deleteGymsImages();
        } else {
            $this->error('Please specify an option: --all, --sliders, --avatars, or --gyms');
        }
    }

    protected function deleteAllImages(): void
    {
        $this->deleteSlidersImages();
        $this->deleteAvatarsImages();
        $this->deleteGymsImages();
        # ##### ##### #####
        $this->info('All images deleted successfully');
    }

    protected function deleteSlidersImages(): void
    {
        $directory = $this->publicPath . '/sliders';
        $this->deleteImagesInDirectory($directory);
        $this->info('Sliders images deleted successfully');
    }

    protected function deleteAvatarsImages(): void
    {
        $directory = $this->publicPath . '/avatars';
        $this->deleteImagesInDirectory($directory);
        $this->info('Avatars images deleted successfully');
    }

    protected function deleteGymsImages(): void
    {
        $directory = $this->publicPath . '/gyms';
        $this->deleteImagesInDirectory($directory);
        $this->info('Gyms images deleted successfully');
    }

    protected function deleteImagesInDirectory($directory): void
    {
        if (file_exists($directory)) {
            $files = glob($directory . '/*');

            if ($files !== false) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
        }
    }

    protected function getPublicPath(): string
    {
        return public_path();
    }
}
