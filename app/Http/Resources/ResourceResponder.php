<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResourceResponder
{
    public function send($data, string $resourceClass)
    {
        if($data instanceof JsonResponse){
            return $data;
        }

        if (
            (is_a($data, Collection::class)
                || is_a($data, \Illuminate\Database\Eloquent\Collection::class)
                || is_a($data, \Illuminate\Pagination\LengthAwarePaginator::class)
            )

            && !is_a($resourceClass, ResourceCollection::class, true)
        ) {
            return $resourceClass::collection($data);
        }

        return new $resourceClass($data);
    }
}
