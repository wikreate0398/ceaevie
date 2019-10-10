@extends('layouts.public')

@section('content')
    <section class="oferte_plasate _1">
        <div class="container" style="min-height: 350px;">

            @if($transactions->count())
                <div class="tab-content">
                    <div class="table-head">
                        <div class="item" data-number="1">{{ \Constant::get('CREATED_DATE') }}</div>
                        <div class="item" data-number="2"><span class="darken">{{ \Constant::get('TRANS_TYPE') }}</span></div>
                        <div class="item" data-number="3">{!! nl2br(\Constant::get('AUCTION_CODE')) !!}</div>
                        <div class="item" data-number="4">{{ \Constant::get('TRANS_VALUE') }}</div>
                    </div>
                    <div class="table-body">
                        @foreach($transactions as $transaction)
                            <div class="result_item">
                                <div class="item" data-number="1">
                                    {{ $transaction->created_at->format('d.m.Y H:i') }}
                                </div>
                                <div class="item" data-number="2">
                                    <span class="title">{{ $transaction->type_data["name_$lang"] }}</span>
                                </div>
                                <div class="item" data-number="3">{{ $transaction->product_code }}</div>
                                <div class="item" data-number="4">
                                    @if($transaction->type == 'off')
                                        <span class="red">-{{ $transaction->price }} LEI</span>
                                    @else
                                        <span class="blue">{{ $transaction->price }} LEI</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{ $transactions->links() }} 
            @else
                <div class="alert alert-error">
                    {{ \Constant::get('NO_TRANS') }}
                </div>
            @endif
 
        </div>
    </section> 
@stop