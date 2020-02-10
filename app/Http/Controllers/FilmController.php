<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'resource_id.required' => 'No input provided for films',
            'resource_id.unique' => 'Selected film is already saved',
            'resource_id.numeric' => 'Please provide numeric data for film id',
            'resource_id.digits_between' => 'Film id should be between 1 to 7',
        ];

        $request->validate([
            'resource_id' => 'required|unique:films,id|numeric|digits_between:1,7',
        ], $messages);

        $api_url = Config::get('app.sw_api_url');

        if (empty($api_url)) {
            return response()->json(["success" => false, "message" => "API url does not exists"], 422);
        }

        $film = new Film();
        $film->setFilmId($request->input('resource_id'));
        $endpoint = $api_url . "films/".$film->getFilmId();

        try {
            $response = file_get_contents($endpoint);
        } catch (\Exception $exception) {
            return response()->json(["success" => false, "message" => "Unable to get data from API"], 422);
        }

        //TODO:Use execute api method instead of file get contents
        //$response = execute_api($endpoint, "GET");

        $data = json_decode("[".$response."]")[0];

        //set film array
        $options = array();
        $options["id"] = $film->getFilmId();
        $options["title"] = $data->title;
        $options["episode_id"] = $data->episode_id;
        $options["opening_crawl"] = $data->opening_crawl;
        $options["director"] = $data->director;
        $options["producer"] = $data->producer;
        $options["release_date"] = $data->release_date;
        $options["url"] = $data->url;

        $characters = array();
        foreach ($data->characters as $character) {
            $uri_parts = explode('/', $character);
            $characters[] = $uri_parts[count($uri_parts) - 2];
        }

        $response = $film->saveFilm($options);

        // TODO: use pivot relation to store the related data with character details
        // $film->characters()->attach($characters);
        // $film->characters()->save(new Character($characters));

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function show(Film $film)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function edit(Film $film)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Film $film)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Film  $film
     * @return \Illuminate\Http\Response
     */
    public function destroy(Film $film)
    {
        //
    }
}
