<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\TwigTest;
use Twig\Environment;
use Twig\TwigFunction as TF;

class AppExtension extends AbstractExtension
{
    private string $photosBasepath;

    public function __construct(string $photosBasepath)
    {
        $this->photosBasepath = $photosBasepath;
    }

    public function getGlobals(): array
    {
        return [
            'photos_basepath' => $this->photosBasepath,
        ];
    }
}
