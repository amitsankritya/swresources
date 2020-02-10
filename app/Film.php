<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    //

    protected $fillable = ["id", "title", "episode_id", "opening_crawl", "director", "producer", "release_date", "url"];

    private $film_id = 0;

    public function setFilmId($film_id) {
        if (is_numeric($film_id) && ($film_id > 0 || $film_id < 8)) {
            return $this->film_id = (int)$film_id;
        }
    }

    public function getFilmId() {
        return $this->film_id;
    }

    public function saveFilm(array $options = [])
    {
        try {
            $this->id = $options["id"];
            $this->title = $options["title"];
            $this->episode_id = $options["episode_id"];
            $this->opening_crawl = $options["opening_crawl"];
            $this->director = $options["director"];
            $this->producer = $options["producer"];
            $this->release_date = $options["release_date"];
            $this->url = $options["url"];
            $this->save();
            return array("success" => true, "message" => "Data saved successfully");
        } catch (\Exception $exception) {
            return array("success" => false, "message" => "Unable to save data, please try again later");
            //TODO: Log error here
        }
    }

    public function characters() {
        return $this->belongsToMany(Character::class);
    }
}
