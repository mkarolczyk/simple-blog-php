<?php

declare(strict_types=1);

namespace App\Blog\Application\Service;

interface ParameterBagInterface
{
    public function getProjectDir(): string;

    public function getImageDir(): string;

    public function getImageTempDir(): string;
}
