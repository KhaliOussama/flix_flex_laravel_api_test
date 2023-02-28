<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ResponseApiController as ResponseApiController;

class MoviesApiController extends ResponseApiController
{
    /* get all movies api function*/
    public function getAllMovies(Request $request)
    {
        $response = Http::get('http://api.themoviedb.org/3/discover/movie?api_key=YOUR_TMDB_API_KEY');        
        $order_results_by_vote_average = json_decode(collect($response->json()['results'])->sortByDesc('vote_average'));
        $top_five = array_slice((array) $order_results_by_vote_average, 0, 5);
        $data['movies'] = array_chunk($response->json()['results'],10);
        $data['top_five'] = $top_five;
        return $this->sendResponse($data, 'data fetched successfully.');
    }

    /* get a specific movie details api function*/
    public function getMovieDetails($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/movie/'.$id.'?api_key=YOUR_TMDB_API_KEY');
        if($response->successful()){
            
            return $this->sendResponse($response->json(), 'data fetched successfully.');
            
        }elseif($response->failed()){
            
            return $this->sendError("The Requested Movie doesn't exist !");
        }
    }
    /* get a specific movie trailer api function*/
    public function getMovieTrailer($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/movie/'.$id.'/videos?api_key=YOUR_TMDB_API_KEY');
        if($response->successful()){
            $trailer =  collect($response['results'])->where('type','Trailer');
            if(count($trailer)){
                return $this->sendResponse($trailer, 'data fetched successfully.');
                
            }else{
                
                return $this->sendError("The Requested Movie doesn't have a trailer !");
            }
            
        }elseif($response->failed()){
            
            return $this->sendError("The Requested Movie doesn't have a trailer !");
        }
    }
}
