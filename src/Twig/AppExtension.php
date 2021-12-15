<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('price', [$this, 'getPrice']),
            new TwigFilter('ellipsis', [$this, 'makeEllipsis']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('displayFooter', [$this, 'displayFooter'], ['is_safe' => ['html']]),
        ];
    }

    public function getPrice(int|float $number): string
    {
        return number_format($number, 2, ',', ' ') . ' €';
    }

    public function displayFooter(string $text): string
    {
        return "<div>FOOTER - $text</div>";
    }

    public function makeEllipsis(string $text, int $length = 20): string
    {
        $dots = strlen($text) > $length ? '...' : '';
        return substr($text, 0, $length) . $dots;
    }
}
