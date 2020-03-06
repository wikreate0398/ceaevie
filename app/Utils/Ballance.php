<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 22.02.2019
 * Time: 11:52
 */

namespace App\Utils;
use App\Models\Transactions;
use App\Models\Tips;
use App\Models\User;
use function foo\func;

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

        $tipsMoney = self::sumTipAmount(\App\Models\Tips::confirmed($days)
                                                     ->selectRaw('amount, status, created_at, location_work_type, id_location, location_amount')
                                                     ->where((($userType == 'admin') ? 'id_location' : 'id_user'), $idUser)
                                                     ->get(), $userType, $idUser);
        return $tipsMoney+$senderMoneys;
    }

    public static function getPartnerBallance($idPartner, $userType, $days = false)
    {
        $data = User::where('id', $idPartner)->with('referrals.locationUsers')->first();

        foreach ($data->referrals as $referral){
            foreach ($referral->locationUsers as $user){
                $ids[$user->id] = $user->id;
            }
            $ids[$referral->id] = $referral->id;
        }

        $tips = Tips::confirmed($days)
                    ->selectRaw('id, total_amount')
                    ->withPartnerPercent()
                    ->whereIn('id_user', $ids)
                    ->get()
                    ->sum(function($item){
                        return percent($item->total_amount, @$item['percents'][0]->percent);
                    });

        return $tips;
    }

    private static function sumTipAmount($tips, $userType, $userId)
    {
        return $tips->sum(function($tip) use($userType, $userId){
            if(empty($tip->id_location))
            {
                return $tip->amount;
            }
            else
            { 
                if($tip->location_work_type == 'percent')
                { 
                    return ($tip->id_location == $userId) ? $tip->location_amount : $tip->amount;
                }
                else
                {
                    return ($tip->id_location == $userId) ? $tip->amount : 0;
                } 
            }
        });
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