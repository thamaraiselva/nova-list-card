<?php

namespace NovaListCard\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class CardRequest extends NovaRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return string|null|\Laravel\Nova\Resource
     */
    public function findResource(): ?string
    {
        $allCards = new Collection(Nova::$cards);
        collect(Nova::$dashboards)
            ->filter(fn ($dashboard) => method_exists($dashboard, 'cards') && is_array($dashboard->cards()))
            ->each(fn ($dashboard)   => $allCards->push(...$dashboard->cards()));

        return $allCards
            ->filter(fn ($card) => (method_exists($card, 'uriKey') && $card->uriKey() == $this->route('key')))
            ->first()
            ?->resource;
    }

    /**
     * @return string
     */
    public function aggregateKey(): string
    {
        return $this->route('aggregate', 'count');
    }

    /**
     * @return string|null
     */
    public function relationshipKey(): ?string
    {
        return $this->route('relationship');
    }

    /**
     * @return string|null
     */
    public function columnKey(): ?string
    {
        return $this->route('column');
    }

    /**
     * @return string|null
     */
    public function aggregateColumn(): string
    {
        $column = $this->columnKey();

        return "{$this->relationshipKey()}_{$this->aggregateKey()}".($column ? "_{$column}" : '');
    }

    public function prepareQuery(Builder $query): Builder
    {
        if ($this->relationshipKey()) {
            $query = match ($this->aggregateKey()) {
                'count' => $query->withCount($this->relationshipKey()),
                'sum'   => $query->withSum($this->relationshipKey(), $this->columnKey()),
                default => $query
            };
        }

        $query->orderBy($this->input('order_by', $query->getModel()->getKeyName()), $this->input('direction', 'asc'));

        if ($this->has('limit')) {
            $query->take($this->input('limit'));
        }

        return $query;
    }
}
