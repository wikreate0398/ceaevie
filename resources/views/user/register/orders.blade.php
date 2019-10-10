@extends('layouts.public')

@section('content')
    <section class="oferte_plasate _2">
        <div class="container" style="min-height: 350px;">

            @if($orders->count())
            <div class="tab-content">
                <div class="table-head">
                    <div class="item" data-number="1">{{ \Constant::get('CREATED_DATE') }}</div>
                    <div class="item" data-number="2">ID</div>
                    <div class="item" data-number="3"><span class="darken">{{ \Constant::get('ITEM') }}</span></div>
                    <div class="item" data-number="4">{{ \Constant::get('QTY') }}</div>
                    <div class="item" data-number="5">{!! nl2br(\Constant::get('AUCTION_CODE')) !!}</div>
                    <div class="item" data-number="6">{!! nl2br(\Constant::get('COST_ACQ')) !!}</div>
                    <div class="item" data-number="7">{{ \Constant::get('PAYMENT_METHOD') }} </div>
                    <div class="item" data-number="8">{{ \Constant::get('CLIENT_DATA') }}</div>
                    <div class="item" data-number="9"><span class="darken">{{ \Constant::get('STATUS') }}</span></div>
                </div>
                <div class="table-body">
                    @foreach($orders as $order)
                        <div class="result_item">
                            <div class="item" data-number="1">
                                @if($order->ordered_at)
                                    {{ $order->ordered_at->format('d.m.Y') }} <br> {{ $order->ordered_at->format('H:i') }}
                                @else
                                    {{ $order->created_at->format('d.m.Y') }} <br> {{ $order->created_at->format('H:i') }} <br>
                                    <small class="blue">(added to cart)</small>
                                @endif  
                            </div>
                            <div class="item" data-number="2">
                                {{ $order->rand }}
                            </div>
                            <div class="item" data-number="3">
                                <span class="title">{{ $order->auction["name_$lang"] }}</span>
                                <p>{{ $order->auction["name2_$lang"] }}</p>
                            </div>
                            <div class="item" data-number="4">
                                <span class="unity">{{ $order->qty }}</span>
                            </div>
                            <div class="item" data-number="5">
                                {{ $order->auction->code }}
                            </div>
                            <div class="item" data-number="6">
                                <span class="thicker">{{ $order->price }} LEI</span>
                            </div>
                            <div class="item" data-number="7">
                                <span class="thicker">
                                    @if($order->payment_type == 'wallet')
                                        {{ \Constant::get('ELECTRONIC_WALLET') }}
                                    @endif 
                                </span>
                            </div>
                            <div class="item" data-number="8">
                                @if($order->id_provider)
                                    <span class="blue">{{ $order->provider_data["name_$lang"] }}</span>
                                @endif

                                @if($order->phone)
                                    <span class="blue">{{ $order->phone }}</span>
                                @endif 
                            </div>
                            <div class="item" data-number="9">
                                <span class="title {{ $order->status->class }}">
                                    @if($order->id_status == 3 && $order->refund_at)
                                        {{ $order->refund_at->format('d.m.Y H:i') }}
                                    @endif
                                    {{ $order->status["name_$lang"] }}
                                </span>
                            </div>
                        </div>
                    @endforeach 
                </div>
            </div>

                {{ $orders->links() }} 
            @else
                <div class="alert alert-error">
                    {{ \Constant::get('NO_ORDERS') }}
                </div>
            @endif 

        </div>
    </section>
@stop