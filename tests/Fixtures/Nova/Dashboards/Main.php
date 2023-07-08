<?php

namespace NovaListCard\Tests\Fixtures\Nova\Dashboards;

use Illuminate\Support\Str;
use Laravel\Nova\Dashboards\Main as Dashboard;
use NovaListCard\ListCard;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        $cards = [];

        foreach ((new Finder())->in(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Metrics')->files() as $card) {
            $card = '\NovaListCard\Tests\Fixtures\Nova' . str_replace(
                ['/', '.php', '..'],
                ['\\', ''],
                Str::after($card->getPathname(), __DIR__ . DIRECTORY_SEPARATOR)
            );

            if (
                is_subclass_of($card, ListCard::class) &&
                !(new ReflectionClass($card))->isAbstract()
            ) {
                $cards[] = $card::make();
            }
        }

        return $cards;
    }
}
