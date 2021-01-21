<?php

declare(strict_types=1);

namespace App\Blog\Application\Service;

final class ImageFilenameGeneratorService
{
    public static function generate(string $blogPostId, string $extension = 'jpg'): string
    {
        return $blogPostId.'.'.$extension;
    }
}
