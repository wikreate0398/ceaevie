<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 22.02.2019
 * Time: 11:52
 */

namespace App\Utils;
use App\Models\Transactions;

class Ballance
{
    protected $user;

    protected $id_sender;

    private $ballance; 

    private $type;

    private $price;

    private $orderId; 

    private $withdrawId;  

    private $currentBallance;

    function __construct() {}

    public static function getUserBallance($idUser, $userType, $days = false)
    {  
        $senderMoneys = Transactions::where('id_user', $idUser)
                                    ->where('id_sender', '!=', '')
                                    ->forLast($days)
                                    ->where('type', 'replenish')
                                    ->sum('price'); 

        $tipsMoney = sumTipAmount(\App\Models\Tips::confirmed($days)
                                                     ->selectRaw('amount, status, created_at, location_work_type, id_location, location_amount')
                                                     ->where((($userType == 'admin') ? 'id_location' : 'id_user'), \Auth::id())
                                                     ->get(), $userType, $idUser);
        return $tipsMoney+$senderMoneys;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    } 

    public function setSender($id_sender)
    {
        $this->id_sender = $id_sender;
        return $this;
    }  

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function setWithdrawId($withdrawId)
    {
        $this->withdrawId = $withdrawId;
        return $this;
    } 

    public function replenish()
    { 
        $this->ballance = $this->user->ballance + $this->price;
        $this->type     = 'replenish';
        $this->makeTransaction();
    }

    public function off()
    {
        if($this->user->ballance <= $this->price)
        {
            $this->ballance = 0;
        }
        else
        {
            $this->ballance = $this->user->ballance - $this->price;
        }
        $this->type = 'off'; 
        $this->makeTransaction();
    }

    private function makeTransaction()
    {
        $this->user->ballance = $this->ballance;
        $this->user->save();
        $this->saveTransaction();
    } 

    private function saveTransaction()
    {
        Transactions::create([
            'id_user'          => $this->user->id, 
            'id_sender'        => $this->id_sender ?: 0,
            'type'             => $this->type,
            'price'            => $this->price, 
            'ballance'         => $this->user->ballance,
            'id_order'         => $this->orderId ?: '',
            'id_withdraw'      => $this->withdrawId ?: '',
        ]);
    }
}