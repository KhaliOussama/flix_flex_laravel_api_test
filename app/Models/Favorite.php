<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    protected $table = "favorites";

    protected $fillable = ['movie_id', 'serie_id', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
