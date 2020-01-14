<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 

class WithdrawalHistoryExport implements FromView, ShouldAutoSize
{ 
	protected $orders;

	public function __construct($orders)
	{
		$this->orders   = $orders;  
	}

    public function view(): View
    { 
        return view('exports.withdrawal', [
            'data' => $this->orders
        ]);
    } 
}