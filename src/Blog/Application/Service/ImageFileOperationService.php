<?php

declare(strict_types=1);

namespace App\Blog\Application\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class ImageFileOperationService
{
    private const EXCEPTION_FILE_NOT_EXIST = 'File not exist.';

    private ParameterBagInterface $parameterBag;
    private Filesystem $filesystem;

    public function __construct(ParameterBagInterface $parameterBag, Filesystem $filesystem)
    {
        $this->parameterBag = $parameterBag;
        $this->filesystem = $filesystem;
    }

    public function copyToTempDir(string $sourceImagePath, string $imageName): string
    {
        $imageFilename = ImageFilenameGeneratorService::generate($imageName);
        $targetPath = $this->parameterBag->getImageTempDir().'/'.$imageFilename;

        if ($this->filesystem->exists($sourceImagePath)) {
            $this->filesystem->copy($sourceImagePath, $targetPath);
        } else {
            throw new \RuntimeException(self::EXCEPTION_FILE_NOT_EXIST);
        }

        return $targetPath;
    }

    public function moveToPublicDir(string $sourceImagePath, string $imageName): string
    {
        $imageFilename = ImageFilenameGeneratorService::generate($imageName);
        $targetPath = $this->parameterBag->getImageDir().'/';

        if ($this->filesystem->exists($sourceImagePath)) {
            $image = new File($sourceImagePath);
            $image->move($targetPath, $imageFilename);
        } else {
            throw new \RuntimeException(self::EXCEPTION_FILE_NOT_EXIST);
        }

        return $imageFilename;
    }
}
