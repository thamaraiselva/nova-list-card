<?php

namespace NovaListCard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Nova;
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

        return Cache::remember(
            $card->getCacheKey($cardRequest),
            $card->cacheFor(),
            function () use ($cardRequest, $resource) {
                return $resource::indexQuery(
                    $cardRequest,
                    $cardRequest->prepareQuery($resource::newModel()->query())
                )
                    ->get()
                    ->mapInto($resource)
                    ->filter(function ($resource) use ($cardRequest) {
                        return $resource->authorizedToView($cardRequest);
                    })
                    ->map(callback: fn($resource) => [
                        'resource'      => $resource->resource->toArray(),
                        'resourceName'  => $resource::uriKey(),
                        'resourceTitle' => $resource::label(),
                        'title'         => $resource->title(),
                        'subTitle'      => $resource->subtitle(),
                        'resourceId'    => $resource->getKey(),
                        'url'           => route('nova.pages.detail', [$resource::uriKey(), $resource->getKey()]),
                        'avatar'        => $resource->resolveAvatarUrl($cardRequest),
                        'aggregate'     => data_get($resource, $cardRequest->aggregateColumn()),
                    ]);
            }
        );
    }
}
