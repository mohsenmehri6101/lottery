<?php
namespace Modules\Article\Console;

use Illuminate\Console\Command;
class ImageFakeDeleteCommand extends Command
{
    protected $signature = 'article:delete-images {--all : Delete all images} {--sliders : Delete sliders images} {--slider : Delete sliders images} {--avatars : Delete avatars images} {--avatar : Delete avatars images} {--articles : Delete articles images} {--article : Delete articles images}';
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
        } elseif ($this->option('articles') || $this->option('article')) {
            $this->deleteArticlesImages();
        } else {
            $this->error('Please specify an option: --all, --sliders, --avatars, or --articles');
        }
    }

    protected function deleteAllImages(): void
    {
        $this->deleteSlidersImages();
        $this->deleteAvatarsImages();
        $this->deleteArticlesImages();
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

    protected function deleteArticlesImages(): void
    {
        $directory = $this->publicPath . '/articles_images';
        $this->deleteImagesInDirectory($directory);
        $this->info('Articles images deleted successfully');
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
