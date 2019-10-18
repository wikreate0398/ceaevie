@extends('layouts.public')

@section('content')
<section class="pt-90 pb-90 pay-tip-page loader-v2-inner">
        <div class="flip-square-loader mx-auto"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center message-page"> 
                    <img src="/img/logo.png" alt="logo"> 
                    <p class="grey">{{ $message }}</p>
                    <a href="/" class="">На главную</a>
                </div> 
            </div>
        </div>
    </section>

    <style>
        .message-page{
            min-height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            flex-direction: column;
        } 

        .message-page p{
            margin: 30px 0 !important;
            font-size: 22px !important;
        }

        .message-page a{
            font-weight: bold;
            font-size: 18px;
            text-decoration: underline;
        }

    </style>
@stop

