<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ResponseApiController as ResponseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SeriesApiController extends ResponseApiController
{
    /* get all series api function*/
    public function getAllTvShows(Request $request)
    {
        $response = Http::get('http://api.themoviedb.org/3/discover/tv?api_key=YOUR_TMDB_API_KEY');

        $top_five = collect($response['results'])->sortByDesc('vote_average')->take(5);
        $data['series'] = $response['results'];
        $data['top_five'] = $top_five;
        return $this->sendResponse($data, 'data fetched successfully.');
    }

    /* get a specific serie details api function*/
    public function getSerieDetails($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/tv/'.$id.'?api_key=YOUR_TMDB_API_KEY');
        if($response->successful()){
            
            return $this->sendResponse($response->json(), 'data fetched successfully.');
            
        }elseif($response->failed()){
            
            return $this->sendError("The Requested Serie doesn't exist !");
        }
    }

    /* get a specific serie trailer api function*/
    public function getSerieTrailer($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/tv/'.$id.'/videos?api_key=YOUR_TMDB_API_KEY');
        if($response->successful()){
            $trailer =  collect($response['results'])->where('type','Trailer');
            if(count($trailer)){
                return $this->sendResponse($trailer, 'data fetched successfully.');
                
            }else{

                return $this->sendError("The Requested Serie doesn't have a trailer !");
            }
            
        }elseif($response->failed()){
            
            return $this->sendError("The Requested Serie doesn't have a trailer !");
        }
    }
}
