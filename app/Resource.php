<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    //

    const RESOURCE_FILM = "films";
    const RESOURCE_CHARACTER = "people";

    const RESOURCES = [
        self::RESOURCE_FILM,
        self::RESOURCE_CHARACTER
    ];
}
