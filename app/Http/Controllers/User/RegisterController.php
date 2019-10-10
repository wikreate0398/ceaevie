<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transactions\Transactions;
use Illuminate\Support\Facades\Auth;
use App\Models\Auctions\Bids;
use App\Models\Order;

class RegisterController extends Controller
{
    public function offersPlaced()
    {
        $bids = Bids::where('id_user', \Auth::user()->id)->orderBy('id', 'desc')->paginate(15);
        return view('user.register.offers_placed', compact(['bids']));
    }

    public function transactions()
    {
        $transactions = Transactions::where('id_user', Auth::id())->orderBy('id', 'desc')->paginate(15);  
        return view('user.register.transactions', compact(['transactions']));
    }

    public function orders()
    {  
        $orders = Order::where('id_user', \Auth::user()->id)->orderStage()->paginate(15);
        return view('user.register.orders', compact(['orders']));
    }
}