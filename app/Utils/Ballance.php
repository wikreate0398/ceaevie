<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 22.02.2019
 * Time: 11:52
 */

namespace App\Utils;
use App\Models\Transactions\Transactions;

class Ballance
{
    protected $user;

    private $ballance;

    private $transactionType;

    private $type;

    private $price;

    private $orderId;

    private $productCode;

    private $currentBallance;

    function __construct($user)
    {
        $this->user = $user;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function setProductCode($product_code)
    {
        $this->productCode = $product_code;
        return $this;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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

    public function transactionType($type)
    {
        $this->transactionType = $type;
        return $this;
    }

    private function saveTransaction()
    {
        Transactions::create([
            'id_user'          => $this->user->id,
            'transaction_type' => $this->transactionType,
            'type'             => $this->type,
            'price'            => $this->price,
            'product_code'     => $this->productCode,
            'ballance'         => $this->user->ballance,
            'order_id'         => $this->orderId
        ]);
    }
}