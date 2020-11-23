<?php

namespace App\Twig;

use App\Entity\AbstractLineItem;
use App\Entity\FixedCostLineItem;
use App\Entity\HourRangeLineItem;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

class AppExtension extends AbstractExtension
{
    public function getTests()
    {
        return [
            new TwigTest('hour_range', static fn(AbstractLineItem $item) => $item instanceof HourRangeLineItem),
            new TwigTest('fixed_cost', static fn(AbstractLineItem $item) => $item instanceof FixedCostLineItem),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('hours', static fn(float $n) => str_replace('.00', '', number_format($n, 2))),
        ];
    }

}
