<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    //
    protected $fillable = ["id", "name", "height", "mass", "hair_color", "eye_color", "birth_year", "gender", "url"];

    public function films() {
        return $this->hasMany(Film::class);
    }
}
