<?php

namespace NovaListCard\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use NovaListCard\Http\Requests\CardRequest;

/**
 * @psalm-suppress UndefinedClass
 */
class ResourceController extends Controller
{
    /**
     * @param \NovaListCard\Http\Requests\CardRequest $cardRequest
     * @return mixed
     */
    public function __invoke(CardRequest $cardRequest)
    {

        $callback = function () use ($cardRequest) {
            try {
                $resource = $cardRequest->cardResource();
            } catch (\Exception $e) {
                return Response::json([
                    'message' => $e->getMessage(),
                ], 404);
            }

            return $cardRequest->cardResource()::indexQuery(
                $cardRequest,
                $cardRequest->prepareQuery()
            )
                ->get()
                ->mapInto($cardRequest->cardResource())
                ->filter(function ($resource) use ($cardRequest) {
                    return $resource->authorizedToView($cardRequest);
                })
                ->map(callback: function ($resource) use ($cardRequest) {
                    $data = [
                        'title' => $resource->title(),
                        'url'   => route('nova.pages.detail', [$resource::uriKey(), $resource->getKey()]),
                    ];

                    $card = $cardRequest->card();

                    if ($card->valueColumn) {
                        $value         = $resource->resource->{$card->valueColumn};
                        $data['value'] = match ($card->valueFormatter) {
                            'datetime' => Carbon::parse($value)->format($card->valueFormat),
                            'integer'  => (int)$value,
                            default    => $value,
                        };
                    }

                    if ($card->timestampColumn) {
                        $data['timestamp'] = $resource->resource->{$card->timestampColumn}?->format($card->timestampFormat ?: 'Y-m-d');
                    }

                    return $data;
                });
        };

        if ($cacheFor = $cardRequest->card()?->cacheFor()) {
            return Cache::remember(
                $cardRequest->card()->getCacheKey($cardRequest),
                $cacheFor,
                $callback
            );
        }

        return call_user_func($callback);
    }
}
