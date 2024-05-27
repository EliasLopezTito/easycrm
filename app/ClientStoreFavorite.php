<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ClientStoreFavorite extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'client_id', 'user_id' ];

    protected $dates = ['deleted_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    static function storesFavorites($id)
    {
        $data = DB::table('client_store_favorites')
            ->join('users', 'client_store_favorites.user_id', '=', 'users.id')
            ->join('images', 'users.image_logo_id', '=', 'images.id')
            ->select('client_store_favorites.id as idFavorite', 'client_store_favorites.user_id as user_id', 'users.*', 'images.name as image_name')
            ->where('client_store_favorites.client_id', $id)
            ->whereNull('client_store_favorites.deleted_at')
            ->orderby('client_store_favorites.created_at', 'desc')
            ->get();

        return $data;
    }
}
