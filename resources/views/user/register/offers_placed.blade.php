@extends('layouts.public')

@section('content')
        <section class="oferte_plasate">
        <div class="container" style="min-height: 350px;">

            @if($bids->count())
                <div class="tab-content">
                    <div class="table-head">
                        <div class="item" data-number="1">{{ \Constant::get('CREATED_DATE') }}</div>
                        <div class="item" data-number="2"><span class="darken">{{ \Constant::get('ITEM') }}</span></div>
                        <div class="item" data-number="3">{!! nl2br(\Constant::get('AUCTION_CODE')) !!}</div>
                        <div class="item" data-number="4"><span class="darken">{!! nl2br(\Constant::get('REG_BID')) !!}</span></div>
                        <div class="item" data-number="5">{{ \Constant::get('PAYMENT_METHOD') }}</div>
                    </div>
                    <div class="table-body">
                        
                        @foreach($bids as $bid)  
                            <div class="result_item">
                                <div class="item" data-number="1">
                                    {{ $bid->created_at->format('d.m.Y') }} <br> {{ $bid->created_at->format('H:i') }}
                                </div>
                                <div class="item" data-number="2">
                                    <span class="title">{{ $bid->auction["name_$lang"] }}</span>
                                    <p>{{ $bid->auction["name2_$lang"] }}</p>
                                </div>
                                <div class="item" data-number="3">{{ $bid->auction->code }}</div>
                                <div class="item" data-number="4">
                                    <span class="price">{{ $bid->price }} LEI</span>
                                </div>
                                <div class="item" data-number="5">{{ \Constant::get('ELECTRONIC_WALLET') }}</div>
                            </div>
                        @endforeach 

                    </div>
                </div>

                {{ $bids->links() }}  

            @else
                <div class="alert alert-error">
                    <p>{{ \Constant::get('NO_BIDS') }}</p>
                </div>
            @endif

        </div>
    </section>
@stop