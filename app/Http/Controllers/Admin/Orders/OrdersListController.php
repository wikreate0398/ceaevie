<?php

namespace App\Http\Controllers\Admin\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order; 
use App\Models\OrderStatus; 
use App\Models\User; 
use App\Notifications\ChangedOrderStatus;
use App\Utils\Ballance;

class OrdersListController extends Controller
{

    private $method = 'orders/orders-list';

    private $folder = 'orders.orders_list'; 

    private $redirectRoute = 'admin_orders_list'; 

    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new Order;
        $this->method = config('admin.path') . '/' . $this->method;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {  
        $data = [
            'data'    => $this->model->orderStage()->where('id_status', '!=', 5)->filter()->get(),
            'table'   => $this->model->getTable(),
            'method'  => $this->method,
            'status'  => OrderStatus::orderByRaw('page_up asc, id desc')->get(),
            'clients' => User::has('order')->orderBy('id', 'desc')->get()
        ]; 

        Order::where('open', '1')->update(['open' => 0]);

        return view('admin.'.$this->folder.'.list', $data);
    }   

    public function changeStatus($id, Request $request, $returnData = [])
    { 
        $order            = Order::whereId($id)->firstOrFail(); 
        $order->id_status = $request->value;
        $order->save();
        if ($request->value == 3) // refund
        {
            $order->refund_at = date('Y-m-d H:i:s');
            $order->save();
 
            (new Ballance($order->user))
                ->transactionType('refund_payment')
                ->setPrice($order->price)
                ->setProductCode($order->auction->code)
                ->setOrderId($order->id)
                ->replenish();

            $returnData = ['refund_at' => $order->refund_at->format('d.m.Y H:i'), 'class' => $order->status->class];

            \Bus::dispatch(
                new \App\Console\Commands\CloseCartItem($order)
            );
        }
        elseif ($request->value == 4) // cancel
        {
            (new Ballance($order->user))
                ->transactionType('cancel_payment')
                ->setPrice($order->price)
                ->setProductCode($order->auction->code)
                ->setOrderId($order->id)
                ->replenish();

            \Bus::dispatch(
                new \App\Console\Commands\CancelCartItem($order)
            );

            // change auction qty
            $order->auction->quantity = $order->auction->quantity + $order->qty;
            $order->auction->save();
        } 
        elseif ($request->value == 5) // reject
        {
            \Bus::dispatch(
                new \App\Console\Commands\CloseCartItem($order)
            );
        }

        $order->user->notify(new ChangedOrderStatus($order)); 

        return $returnData;
    } 
}
