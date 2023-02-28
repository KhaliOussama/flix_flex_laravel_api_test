<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MoviesApiController;
use App\Http\Controllers\Api\SeriesApiController;
use App\Http\Controllers\Api\RegisterApiController;
use App\Http\Controllers\Api\FavoritesApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(RegisterApiController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group(function(){
    
    /* Get All Movies API Route */
    
    Route::get('/movies',[MoviesApiController::class, 'getAllMovies']);

    /* Get All Series API Route */
    
    Route::get('/series',[SeriesApiController::class, 'getAllTvShows']);

    /* get Favorites list items API Route */

    Route::get('/favorites/items',[FavoritesApiController::class, 'getFavoritesListItems']);
    
    /* Add Movie to Favorites list API Route */

    Route::post('/favorites/movies/{id}/add',[FavoritesApiController::class, 'favoritesListAddMovie']);
    
    /* Add Serie to Favorites list API Route */

    Route::post('/favorites/tv/{id}/add',[FavoritesApiController::class, 'favoritesListAddSerie']);

    /* Delete Serie from Favorites list API Route */
    
    Route::post('/favorites/movie/{id}/remove',[FavoritesApiController::class, 'favoritesListRemoveMovie']);

    /* Delete Serie from Favorites list API Route */
    
    Route::post('/favorites/tv/{id}/remove',[FavoritesApiController::class, 'favoritesListRemoveSerie']);
    
    /* Get Movie details API Route */
    
    Route::get('/movies/{id}',[MoviesApiController::class, 'getMovieDetails']);
    
    /* Get Serie details API Route */
    
    Route::get('/series/{id}',[SeriesApiController::class, 'getSerieDetails']);
    
    /* Get Movie Trailer API Route */
    
    Route::get('/trailer/movies/{id}',[MoviesApiController::class, 'getMovieTrailer']);
    
    /* Get Serie Trailer API Route */
    
    Route::get('/trailer/series/{id}',[SeriesApiController::class, 'getSerieTrailer']);
    
    /* search movie or serie API Route */
    
    Route::post('/search',[FavoritesApiController::class, 'searchMoviesOrSeries']);
});

