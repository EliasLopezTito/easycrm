<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ClientFavorite extends Model
{
    use SoftDeletes;

    protected $fillable = [ 'client_id', 'product_id' ];

    protected $dates = ['deleted_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    static function productsFavorites($id)
    {
        $data = DB::table('client_favorites')
            ->join('products', 'client_favorites.product_id', '=', 'products.id')
            ->join('images', 'products.image_id', '=', 'images.id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->select('client_favorites.id as idFavorite', 'client_favorites.product_id as product_id', 'products.*', 'images.name as image_name', 'users.name as user_name')
            ->where('client_favorites.client_id', $id)
            ->whereNull('client_favorites.deleted_at')
            ->whereNull('products.deleted_at')
            ->orderby('client_favorites.created_at', 'desc')
            ->get();

        return $data;
    }
}
