<?php

namespace NovaListCard\Tests\ListCardTests;

use Illuminate\Support\Collection;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use NovaListCard\ListCard;
use NovaListCard\Tests\TestCase;

class ListCardTestCase extends TestCase
{
    protected ?Collection $allCards = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->allCards = new Collection();
        ServingNova::dispatch(new NovaRequest());
        collect(Nova::$dashboards)
            ->filter(fn ($dashboard) => method_exists($dashboard, 'cards') && is_array($dashboard->cards()))
            ->each(fn ($dashboard) => $this->allCards->push(...$dashboard->cards()));
    }

    /**
     * @param class-string<ListCard> $className
     * @return ListCard|null
     */
    protected function getCard(string $className): ?ListCard
    {
        return $this->allCards->first(fn ($i) => ($i instanceof $className));
    }
}
