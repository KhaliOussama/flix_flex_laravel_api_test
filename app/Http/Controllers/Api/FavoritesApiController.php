<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ResponseApiController as ResponseApiController;

class FavoritesApiController extends ResponseApiController
{
    // add a specific movie to favorites list
    public function favoritesListAddMovie($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/movie/'.$id.'?api_key=YOUR_TMDB_API_KEY');
        if($response->successful()){
            
            if( count(Favorite::where('movie_id',$id)->get()))
            {

                return $this->sendError("This movie already exists in the favorites list !");

            }else{

                $favorite = new Favorite;
                $favorite->movie_id = $id;
                $favorite->user_id = auth('sanctum')->user()->id;
                $favorite->save();
            }
            return $this->sendResponse([], 'The movie '.$response['title'].' added to favorites list successfully !');
        
        }else{

            return $this->sendError("This movie doesn't exist in the list!");
        }
        
    }

    // add a specific serie to favorites list
    public function favoritesListAddSerie($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/tv/'.$id.'?api_key=YOUR_TMDB_API_KEY');

        if($response->successful()){
            
            if(count(Favorite::where('serie_id',$id)->get()))
            {

                return $this->sendError("This serie already exists in the favorites list!.");

            }else{

                $favorite = new Favorite;
                $favorite->serie_id = $id;
                $favorite->user_id = auth('sanctum')->user()->id;
                $favorite->save();
            }
            return $this->sendResponse([], 'The serie '.$response['original_name'].' added to favorites list successfully!.');
        
        }else{

            return $this->sendError("This serie doesn't exist in the list!.");
        }
    }
    // remove a specific movie from favorites list
    public function favoritesListRemoveMovie($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/movie/'.$id.'?api_key=YOUR_TMDB_API_KEY');

        if($response->successful()){
            $favorites_list_items = auth('sanctum')->user()->favoriteList;
            $found=false;
            foreach($favorites_list_items as $item){
                if($item->movie_id == $id)
                {
                    $found = true;
                    $item->delete();
                }
            }
            if($found == true)
            {

                return $this->sendResponse([],$response['title']." successfully removed from the favorites list !.");

            }else{
                return $this->sendError("This movie doesn't exist in the list!.");
            }
        
        }else{
            return $this->sendError("This movie doesn't exist in the list!.");
            
        }
    }
    // remove a specific serie from favorites list
    public function favoritesListRemoveSerie($id)
    {
        $response = Http::get('http://api.themoviedb.org/3/tv/'.$id.'?api_key=YOUR_TMDB_API_KEY');

        if($response->successful()){
            $favorites_list_items = auth('sanctum')->user()->favoriteList;
            $found=false;
            /* check if the element exists in the favorites list */
            foreach($favorites_list_items as $item){
                /* remove item if founded in the list  */
                if($item->serie_id == $id)
                {
                    $found = true;
                    $item->delete();
                }
            }
            /* if the item founded in the list return successful response */
            if($found == true)
            {
                return $this->sendResponse([],$response['original_name']." successfully removed from the favorites list !.");
                
            }else{
                /* if the item not founded in the list return that it doesn't exists in the list */
                return $this->sendError("This Serie doesn't exist in the list!.");
            }
        
        }else{
            return $this->sendError("This Serie doesn't exist in the list!.");
            
        }
    }

    //get all items of favorites list of movies and series

    public function getFavoritesListItems()
    {
        $favorites_list_db_items = Favorite::all();
        $favorite_list_items = [];

        if(count($favorites_list_db_items))
        {
            foreach($favorites_list_db_items as $item)
            {
                if($item->movie_id)
                {

                    $response = Http::get('http://api.themoviedb.org/3/movie/'.$item->movie_id.'?api_key=YOUR_TMDB_API_KEY');
                    if($response->successful())
                    {
                        array_push($favorite_list_items,$response->json());
                    }elseif($response->failed()){
                        $item->delete();
                    }
                    
                }
                elseif($item->serie_id)
                { 
                    $response = Http::get('http://api.themoviedb.org/3/tv/'.$item->serie_id.'?api_key=YOUR_TMDB_API_KEY');
                    if($response->successful())
                    {
                        array_push($favorite_list_items,$response->json());
                    }elseif($response->failed()){
                       $item->delete();
                    }
                }
            }
            return $this->sendResponse($favorite_list_items,"Favorites list items loaded successfully !.");

        }else{
            return $this->sendResponse([],"The favourites list is empty!.");
        }
    }

    // searching movies or series

    public function searchMoviesOrSeries(Request $request)
    {
        $response = Http::get('http://api.themoviedb.org/3/search/multi?api_key=YOUR_TMDB_API_KEY&query='.$request['query']);
        if($response->successful())
        {
            if(count($response->json())){
                $data = $response->json();
                return $this->sendResponse($data, 'data fetched successfully.');
                
            }else{
                return $this->sendResponse([], 'your search is empty.');
                
            }

        }elseif($response->failed()){

            return $this->sendError("the search has failed !.");
        }
                    
    }
}
