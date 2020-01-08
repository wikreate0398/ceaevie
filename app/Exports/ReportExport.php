<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 

class ReportExport implements FromView, ShouldAutoSize
{ 
	protected $orders, $percents;

	public function __construct($orders, $percents)
	{
		$this->orders   = $orders; 
        $this->percents = $percents;  
	}

    public function view(): View
    { 
        return view('exports.report', [
            'orders'   => $this->orders, 
            'percents' => $this->percents,  
        ]);
    } 
}