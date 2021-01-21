<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure;

use App\Blog\Application\Service\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface as ParameterBagInterfaceSymfony;

final class ParameterBag implements ParameterBagInterface
{
    private ParameterBagInterfaceSymfony $parameterBag;

    public function __construct(ParameterBagInterfaceSymfony $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getProjectDir(): string
    {
        return $this->parameterBag->get('kernel.project_dir');
    }

    public function getImageDir(): string
    {
        return $this->parameterBag->get('kernel.project_dir').$this->parameterBag->get('app_path_image');
    }

    public function getImageTempDir(): string
    {
        return $this->parameterBag->get('kernel.project_dir').$this->parameterBag->get('app_temp_path_image');
    }
}
