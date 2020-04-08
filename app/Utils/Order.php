<?php

namespace App\Utils;

use App\Models\EnrollmentPercents;
use App\Models\QrCode;
use App\Models\TipPercents;
use App\Models\Tips;

class Order
{
    private $price;

    private $code;

    private $payment = 'payment_center';

    private $rating;

    private $review;

    private $from_bill;

    private $order;

    public function requestData($array)
    {
        foreach ($array as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public final function make()
    {
        $qrCode      = QrCode::where('code', $this->code)->with(['user.agent', 'location.agent'])->first();

        // процент приложния
        $percents    = EnrollmentPercents::select('percent', 'id', 'type')->get()->keyBy('type');

        if ($qrCode->user->agent) {
            $partner = $qrCode->user->agent;
        } else if ($qrCode->user->location->count() && $qrCode->user->location->first()){
            $partner = $qrCode->user->location->first()->agent;
        }

        if (@$partner->fee){
            $incomePercent = $percents['income']->percent;

            if ($partner->fee > $percents['agent']->percent) {
                $incomePercent = ($percents['agent']->percent+$percents['income']->percent) - $partner->fee;
            } else if($partner->fee < $percents['agent']['percent']) {
                $incomePercent = ($percents['agent']->percent - $partner->fee) + $percents['income']->percent;
            }

            if ($incomePercent <> $percents['income']->percent){
                $percents['income']->percent = $incomePercent;
                $percents['agent']->percent = $partner->fee;
            }
        }

        $fee         = $percents->sum('percent');
        $totalAmount = toFloat($this->price);
        $amount      = withdrawFee($totalAmount, $fee);

        $location_fee    = 0;
        $location_amount = 0;
        if (!empty($qrCode->location))
        {
            if ($qrCode->location->work_type == 'percent')
            {
                $location_fee = $qrCode->location->self_percent;
                $location_amount = withdrawFee($amount, $location_fee);
            }
        }

        $tip = Tips::create([
            'id_user'             => $qrCode->id_user,
            'id_location'         => $qrCode->id_location,
            'id_payment'          => ($this->payment == 'payment_center') ? 1 : '',
            'payment_service'     => $this->payment ?: '',
            'id_qrcode'           => $qrCode->id,
            'rand'                => generate_id(7),
            'total_amount'        => $totalAmount,
            'amount'              => $location_amount ? $amount - $location_amount : $amount,
            'location_fee'        => $location_fee,
            'location_amount'     => $location_amount,
            'location_work_type'  => @$qrCode->location->work_type ?: '',
            'fee'                 => $fee,
            'rating'              => $this->rating ?: '',
            'review'              => $this->review ?: '',
            'from_bill'           => $this->from_bill ?: 0
        ]);

        $percents->each(function($percent) use($tip){
            TipPercents::insert([
                'id_tip'     => $tip->id,
                'id_percent' => $percent->id,
                'percent'    => $percent->percent
            ]);
        });

        $this->order = $tip;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getId()
    {
        return $this->order->id;
    }
}