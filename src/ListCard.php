<?php

namespace NovaListCard;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Card;

class ListCard extends Card
{
    public $width = '1/3';

    public $resource;

    public $relationship;

    public $aggregate;

    public $aggregateColumn;

    public int $limit = 5;

    public string $orderColumn = 'created_at';

    public $orderDirection = 'desc';

    public $heading = [];

    public $valueColumn;

    public $valueFormat;

    public $valueFormatter;

    public $timestampEnabled = false;

    public $timestampColumn;

    public $timestampFormat;

    public $classes;

    public function component()
    {
        return 'nova-list-card';
    }

    public function resource($resource)
    {
        $this->resource = $resource;
        $this->classes($resource::uriKey());

        return $this;
    }

    public function withCount($relationship)
    {
        $this->aggregate = 'count';
        $this->relationship = $relationship;

        return $this;
    }

    public function withSum($relationship, $column)
    {
        $this->aggregate = 'sum';
        $this->relationship = $relationship;
        $this->aggregateColumn = $column;

        return $this;
    }

    public function orderBy($column, $direction = 'asc')
    {
        $this->orderColumn = $column;
        $this->orderDirection = $direction;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function heading($left, $right = null)
    {
        $this->heading = [ 'left' => $left, 'right' => $right ];
        $this->classes(Str::slug($left));

        return $this;
    }

    public function value($column, $format = null, $formatter = 'numerial')
    {
        $this->valueColumn = $column;
        $this->valueFormat = $format;
        $this->valueFormatter = $formatter;

        return $this;
    }

    public function timestamp($column = 'created_at', $format = 'MM/DD/YYYY'): ListCard
    {
        $this->timestampEnabled = true;
        $this->timestampColumn = $column;
        $this->timestampFormat = $format;

        return $this;
    }

    /**
     * Add wrapper class
     *
     * @param string $classes
     *
     * @return $this
     */
    public function classes(string $classes): ListCard
    {
        $this->classes = "{$this->classes} {$classes}";

        return $this;
    }

    /**
     * Set each second row with different background color
     *
     * @return $this
     */
    public function zebra(): ListCard
    {
        return $this->classes('zebra');
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'limit' => $this->limit,
            'uri_key' => $this->uriKey(),
            'relationship' => $this->relationship,
            'aggregate' => $this->aggregate,
            'aggregate_column' => $this->aggregateColumn,
            'order_column' => $this->orderColumn,
            'order_direction' => $this->orderDirection,
            'classes' => $this->classes,
            'heading' => $this->heading,
            'value_column' => $this->valueColumn,
            'value_format' => $this->valueFormat,
            'value_formatter' => $this->valueFormatter,
            'timestamp_column' => $this->timestampColumn,
            'timestamp_enabled' => $this->timestampEnabled,
            'timestamp_format' => $this->timestampFormat,
        ], parent::jsonSerialize());
    }

    /**
     * @inheritDoc
     */
    public function authorize(Request $request): bool
    {
        return true; // authorizeToViewAny
    }
}
