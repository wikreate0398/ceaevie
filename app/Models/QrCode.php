<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class QrCode extends Model
{
	public $timestamps = true;

	protected $table = 'qr_codes';

	protected $fillable = [
        'id_user', 
        'card_signature',
        'institution_name', 
        'id_background', 
        'code',
        'qr_code',
        'page_up'
    ]; 
}
