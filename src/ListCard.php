<?php

namespace NovaListCard;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Card;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

class ListCard extends Card
{
    public ?string $name = null;

    public $width = '1/3';

    public ?string $resource = null;

    public ?string $relationship = null;

    public ?string $aggregate = null;

    public ?string $aggregateColumn = null;

    public ?int $limit = 5;

    public string $orderColumn = 'created_at';

    public string $orderDirection = 'desc';

    public array $heading = [];

    public ?string $valueColumn = null;

    public ?string $valueFormat = null;

    public ?string $valueFormatter = null;

    public ?string $timestampColumn = null;

    public ?string $timestampFormat = null;

    public bool $noMaxHeight = false;

    public string $classes = '';

    public function component()
    {
        return 'nova-list-card';
    }

    public function cacheFor(): int|Carbon
    {
        return 0;
    }

    public function getCacheKey(NovaRequest $request): string
    {
        return sprintf(
            'nova.metric.%s.%s.%s.%s.%s.%s.%s',
            $this->uriKey(),
            $request->input('range', 'no-range'),
            $request->input('timezone', 'no-timezone'),
            $request->input('twelveHourTime', 'no-12-hour-time'),
            $this->onlyOnDetail ? $request->findModelOrFail()->getKey() : 'no-resource-id',
            md5($request->input('filter', 'no-filter')),
            md5($this->resource)
        );
    }

    public function name(): string
    {
        return $this->name ?: Nova::humanize($this);
    }

    public function uriKey(): string
    {
        return Str::slug($this->name(), '-', null);
    }

    /**
     * @param class-string<Resource> $resource
     * @return $this
     */
    public function resource(string $resource): static
    {
        $this->resource = $resource;
        $this->classes($resource::uriKey());

        return $this;
    }

    public function withCount($relationship): static
    {
        $this->aggregate    = 'count';
        $this->relationship = $relationship;

        return $this;
    }

    public function withSum(string $relationship, string $column): static
    {
        $this->aggregate       = 'sum';
        $this->relationship    = $relationship;
        $this->aggregateColumn = $column;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'desc'): static
    {
        $this->orderColumn = $column;

        $direction = Str::lower($direction);
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }
        $this->orderDirection = $direction;

        return $this;
    }

    public function limit(?int $limit = 5): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function heading(string $left, ?string $right = null): static
    {
        $this->heading = ['left' => $left, 'right' => $right];
        $this->classes(Str::slug($left));

        return $this;
    }

    public function value(string $column, ?string $formatter = 'integer', ?string $format = null): static
    {
        $this->valueColumn    = $column;
        $this->valueFormat    = $format;
        $this->valueFormatter = $formatter;

        return $this;
    }

    public function timestamp(string $column = 'created_at', string $format = 'm/d/Y'): static
    {
        $this->timestampColumn  = $column;
        $this->timestampFormat  = $format;

        return $this;
    }

    public function noMaxHeight(bool $noMaxHeight = true): static
    {
        $this->noMaxHeight = $noMaxHeight;

        return $this;
    }

    public function classes(string $classes): static
    {
        $this->classes = "{$this->classes} {$classes}";

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'uriKey'            => $this->uriKey(),
            'classes'           => $this->classes,
            'heading'           => $this->heading,
            'noMaxHeight'       => $this->noMaxHeight,
            'url'               => route('nova-list-card.data', array_merge(array_filter([
                'key'          => $this->uriKey(),
                'aggregate'    => $this->aggregate,
                'relationship' => $this->relationship,
                'column'       => $this->aggregateColumn,
            ]), [
                'order_by'       => $this->orderColumn,
                'direction'      => $this->orderDirection,
                'limit'          => $this->limit,
                'nova-list-card' => $this->uriKey(),
            ])),
        ], parent::jsonSerialize());
    }
}
