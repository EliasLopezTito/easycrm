<?php

namespace NavegapComprame;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [  'code', 'user_id', 'client_id', 'type_pay_id', 'type_send_id', 'order_status_id', 'document', 'total' ];

    protected $dates = ['deleted_at'];

    static function getListOrders(){

        $data = DB::table('orders')
            ->join('order_status', 'order_status.id', '=' , 'orders.order_status_id')
            ->select('orders.*', 'order_status.name as status')
            ->where('orders.client_id' , Auth::guard('client')->user()->id)
            ->whereNull('orders.deleted_at')
            ->orderby('id', 'Desc')
            ->get();

        return $data;

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function typePay()
    {
        return $this->belongsTo(TypePay::class);
    }

    public function typeSend()
    {
        return $this->belongsTo(TypeSend::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatu::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

}
