<?php

namespace App\Helpers;



class Paginate {

    static function paginate($paginateObject,$collection,$name ) {

        $record =  [
            "$name" =>$collection,
            "Pagination" =>[
            "current_page" => $paginateObject->currentPage(),
            "per_page" => $paginateObject->perPage(),
            "total" => $paginateObject->total(),
                "links" => [
            "last_page" => $paginateObject->lastPage(),
            "next_page_url" => $paginateObject->nextPageUrl(),
            "prev_page_url" => $paginateObject->previousPageUrl(),
                ]
            ]
        ];
        if ($paginateObject) {
            if ($paginateObject->perPage() < $paginateObject->total()) {
                return $record;
            }
            return $collection;
        }
        return null;
    }
}