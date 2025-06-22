<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CopyImagesToPublic extends Command
{
    protected $signature = 'images:copy-to-public';
    protected $description = 'Копирует все картинки из resources/media/images в public/media/images';

    public function handle()
    {
        $from = resource_path('media/images');
        $to = public_path('media/images');

        if (!File::exists($from)) {
            $this->error('Папка источника не найдена: ' . $from);
            return 1;
        }

        if (!File::exists($to)) {
            File::makeDirectory($to, 0755, true);
            $this->info('Создана папка назначения: ' . $to);
        }

        $files = File::files($from);
        $copied = 0;

        foreach ($files as $file) {
            $target = $to . DIRECTORY_SEPARATOR . $file->getFilename();
            File::copy($file->getPathname(), $target);
            $copied++;
        }

        $this->info("Скопировано файлов: $copied");
        return 0;
    }
} 