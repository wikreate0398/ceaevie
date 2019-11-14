<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class QrCode extends Model
{
    use SoftDeletes;

	public $timestamps = true;

	protected $table = 'qr_codes';

	protected $fillable = [
        'id_user', 
        'id_location',
        'card_signature',
        'institution_name', 
        'id_background', 
        'code', 
        'qr_code',
        'page_up'
    ];

	public function background()
    {
        return $this->hasOne('App\Models\BackgroundColor', 'id', 'id_background');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user');
    } 

    public function location()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_location');
    } 
}
