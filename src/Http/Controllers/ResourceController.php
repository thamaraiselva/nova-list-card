<?php

namespace NovaListCard\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use NovaListCard\Http\Requests\CardRequest;
use NovaListCard\ListCard;

/**
 * @psalm-suppress UndefinedClass
 */
class ResourceController extends Controller
{
    /**
     * @param \NovaListCard\Http\Requests\CardRequest $cardRequest
     * @return mixed
     * @throws \Throwable
     */
    public function __invoke(CardRequest $cardRequest)
    {
        /** @var ListCard $card */
        $card     = $cardRequest->findCard();
        $resource = $card?->resource;

        throw_if(!$card || !$resource);

        $callback = function () use ($cardRequest, $resource, $card) {
            return $resource::indexQuery(
                $cardRequest,
                $cardRequest->prepareQuery($resource::newModel()->query())
            )
                ->get()
                ->mapInto($resource)
                ->filter(function ($resource) use ($cardRequest) {
                    return $resource->authorizedToView($cardRequest);
                })
                ->map(callback: function ($resource) use ($card, $cardRequest) {
                    $data = [
                        'title'         => $resource->title(),
                        'url'           => route('nova.pages.detail', [$resource::uriKey(), $resource->getKey()]),
                    ];

                    if($card->valueColumn) {
                        $value         = $resource->resource->{$card->valueColumn};
                        $data['value'] = match ($card->valueFormatter) {
                            'datetime' => Carbon::parse($value)->format($card->valueFormat),
                            'integer'  => (int) $value,
                            default    => $value,
                        };
                    }

                    if($card->timestampColumn) {
                        $data['timestamp'] = $resource->resource->{$card->timestampColumn}?->format($card->timestampFormat ?: 'Y-m-d');
                    }

                    return $data;
                });
        };

        if ($cacheFor = $card->cacheFor()) {
            return Cache::remember(
                $card->getCacheKey($cardRequest),
                $cacheFor,
                $callback
            );
        }

        return call_user_func($callback);
    }
}
