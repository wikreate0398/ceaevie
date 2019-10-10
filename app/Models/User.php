<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'confirm', 'confirm_hash', 'active', 'account_number', 'ballance', 'lang'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'confirm' => 'integer',
        'active'  => 'integer',
        'ballance' => 'float'
    ];

    public function bids()
    {
        return $this->hasMany('App\Models\Auctions\Bids', 'id_user', 'id')->orderByRaw('price asc, id asc');
    }

    /**
     * Возвращает предложения текущего товара
     *
     * @param $query
     * @return mixed
     */
    public function scopeProposedBids($query, $idAuction)
    {
        return $query->with(['bids' => function($query) use($idAuction){
            return $query->where('prepare_id', 0)->where('id_auction', $idAuction);
        }]);
    }

    public function order()
    {
        return $this->hasMany('App\Models\Order', 'id_user', 'id');
    }

    public function scopeFilter($query)
    {
        if(request()->q)
        {
            $searchQuery = request()->q;
            $query->where('name', 'like', '%'.$searchQuery.'%')
                  ->orWhere('email', 'like', '%'.$searchQuery.'%')
                  ->orWhere('account_number', 'like', '%'.$searchQuery.'%');
        } 

        return $query;
    } 
}
