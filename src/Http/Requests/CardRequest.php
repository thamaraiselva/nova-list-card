<?php

namespace NovaListCard\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use NovaListCard\ListCard;

class CardRequest extends NovaRequest
{
    protected ?ListCard $_card = null;

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


    public function card(): ?ListCard
    {
        if (!$this->_card) {
            $allCards = new Collection();
            collect(Nova::$dashboards)
                ->filter(fn ($dashboard) => method_exists($dashboard, 'cards') && is_array($dashboard->cards()))
                ->each(fn ($dashboard) => $allCards->push(...$dashboard->cards()));

            $this->_card = $allCards
                ->filter(fn ($card) => (method_exists($card, 'uriKey') && $card->uriKey() == $this->route('key')))
                ->first();
        }

        return $this->_card;
    }

    /**
     * @return class-string<Resource>|null
     */
    public function cardResource(): ?string
    {
        return $this->card()?->resource;
    }

    public function prepareQuery(): Builder
    {
        $resource = $this->cardResource();
        if(!$resource) {
            throw new \Exception('Resource not found');
        }

        /** @var Builder $query */
        $query = $resource::newModel()->query();

        $card = $this->card();

        if ($card->relationship) {
            $query = match ($card->aggregate) {
                'count' => $query->withCount($card->relationship),
                'sum'   => $query->withSum($card->relationship, $card->aggregateColumn ?: $card->valueColumn),
                'avg'   => $query->withAvg($card->relationship, $card->aggregateColumn ?: $card->valueColumn),
                'min'   => $query->withMin($card->relationship, $card->aggregateColumn ?: $card->valueColumn),
                'max'   => $query->withMax($card->relationship, $card->aggregateColumn ?: $card->valueColumn),
                default => $query
            };
        }


        if ($card->orderColumn) {
            $query->orderBy($card->orderColumn, $card->orderDirection);
        }

        if (
            ($limit = $card->limit) &&
            $limit > 0
        ) {
            $query->take($limit);
        }

        if (is_callable($card->queryCallback)) {
            call_user_func($card->queryCallback, $query);
        }

        return $query;
    }
}
