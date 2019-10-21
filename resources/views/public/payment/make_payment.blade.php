@extends('layouts.public')

@section('content')
<section class="pt-90 pb-90 pay-tip-page loader-v2-inner">
        <div class="flip-square-loader mx-auto"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                    <img src="/img/logo.png" alt="logo"> 
                    <div class="profile-photo mt-50">
                    	<img src="{{ imageThumb($data->user->image, 'uploads/clients', 181, 181, 0) }}" alt="profile">
                    </div>
                    <h3 class="name">{{ $data->user->name }} <br>{{ $data->user->lastname }}</h3>
                    <p class="grey">{{ $data->card_signature }}</p>
                </div>
                <div class="col-md-6 offset-md-3">
                    <form class="ajax__submit" id="make-payment-form" action="{{ route('make_payment', ['lang' => $lang]) }}">
                    	{{ csrf_field() }}
                        <input type="hidden" name="code" value="{{ $data->code }}">
                          
                            <div class="payment-info">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 mb-30">
                                        <input type="text" id="priceInput" required name="price" class="form-control change_keyup rounded-input price-mask" onfocus="this.placeholder = ''" autocomplete="off" onblur="this.placeholder = 'Сумма'" placeholder="Сумма Pуб." value="{{ request()->price }}">
                                    </div>

                                    <div class="prices">
                                        <button type="button" onclick="setPrice(50)">50 P</button>
                                        <button type="button" onclick="setPrice(100)">100 P</button>
                                        <button type="button" onclick="setPrice(500)">500 P</button>
                                        <button type="button" onclick="setPrice(1000)">1 000 P</button>
                                    </div> 

                                    <div class="col-12 text-center">
                                        <div class="payment-sistems">
                                            <input type="hidden" name="payment" value="" id="payment_type">
                                            @foreach($payments as $payment)
                                                <button type="button" class="btn btn-white" onclick="setPaymentType(this, {{ $payment->id }})">
                                                    <img src="/uploads/payment_types/{{ $payment->image }}" alt="">
                                                </button>
                                            @endforeach 
                                         </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-info" id="Checkout" style="display: none;">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12"> 
                                        <div class="form-group">
                                            <label or="NameOnCard">Имя влядельца <span class="req">*</span></label>
                                            <input id="NameOnCard" name="card[name]" class="form-control" type="text" maxlength="255"> 
                                        </div>
                                        <div class="form-group">
                                            <label for="CreditCardNumber">Номер карты <span class="req">*</span></label>
                                            <input id="CreditCardNumber" name="card[number]" class="form-control" type="text"> 
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="expiry-date-group form-group">
                                                    <label for="ExpiryDate">Срок действия <span class="req">*</span></label>
                                                    <input id="ExpiryDate" name="card[expiry_date]" class="form-control" type="text" placeholder="MM / YY" maxlength="7"> 
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="security-code-group form-group">
                                                <label for="SecurityCode">Код безопасности <span class="req">*</span></label>
                                                    <div class="input-container" >
                                                        <input id="SecurityCode" name="card[cvc]" class="form-control" type="text" >
                                                        <i id="cvc" class="fa fa-question-circle"></i>
                                                    </div>
                                                    <div class="cvc-preview-container two-card hide"> 
                                                        <div class="visa-mc-dis-cvc-preview"></div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div> 
                                    </div>
                                </div>  
                            </div>

                            <div class="row make-payment-btn text-center" style="margin-top: 50px; display: none;">
                                <div class="col-md-5">
                                    <button type="button" class="btn btn-blue btn-v2 btn-back" onclick="toggleBlocks('.card-info, .btn-pay, .btn-back', '.btn-next, .payment-info');">Назад</button> 

                                    <button type="button" class="btn btn-blue btn-v2 btn-next" onclick="toggleBlocks('.btn-next, .payment-info', '.btn-back, .card-info, .btn-pay');">Далее</button> 
                                </div>
                                <div class="col-md-7 btn-pay">
                                    <button type="submit" class="btn btn-blue">Оплатить</button>
                                </div>
                            </div> 
                    </form>

                    <script>
                        function setPaymentType(button, idPayment) {
                            $('#payment_type').val(idPayment);
                            $('.active-payment-type').removeClass('active-payment-type');
                            $(button).addClass('active-payment-type'); 
                            checkPaymentForm();
                        }

                        function setPrice(price) {
                            document.getElementById("priceInput").value = price;
                            checkPaymentForm();
                        }

                        function checkPaymentForm(){
                            var idPayment = $('#payment_type').val();
                            if($('#payment_type').val() && $('#priceInput').val()){ 
                                $('.make-payment-btn').show();
                                toggleBlocks('.btn-back, .btn-pay', '.btn-next');
                            }else{
                                $('.make-payment-btn').hide();
                            }

                            if (idPayment == 1) {   
                            }
                        }

                        $(document).ready(function(){
                            $('#priceInput').change(function(){
                                checkPaymentForm();
                            }); 
                        });
                    </script>

                </div>
            </div>
        </div>
    </section>


    <style>
         
        .make-payment-btn .btn-v2{
            border: 1px solid #219fe0;
            background:transparent;
            color: #219fe0;
        }

        .make-payment-btn .btn-v2:hover{
            color: #219fe0;
            background:none;
        }

        .make-payment-btn{
            display: flex;
            justify-content: center;
        }

        .expiry-date-group input {
          width: calc(100% + 1px);
          border-top-right-radius: 0;
          border-bottom-right-radius: 0;
        }

        .expiry-date-group input:focus {
          position: relative;
          z-index: 10;
        }
 

        .security-code-group input {
          border-top-left-radius: 0;
          border-bottom-left-radius: 0;
        }
 
 
        #Checkout {
          z-index: 100001;
          background: ;
          width: 100%;
          min-width: 300px;
          height: 100%;
          min-height: 100%;   
          margin-left: auto;
          margin-right: auto;
          display: block;
        }

        #Checkout input{
            height: calc(3em + .15rem + 6px);
            font-size: 16px;
        } 
        
        #Checkout label{
            color: #151515;
        }

        .input-container {
          position: relative;
        }

        .input-container input {
          padding-right: 25px;
        }

        .input-container>i, a[role="button"] {
          color: #d3d3d3;
          width: 25px;
          height: 30px;
          line-height: 30px;
          font-size: 16px;
          position: absolute;
          top: 2px;
          right: 2px;
          cursor: pointer;
          text-align: center;
        }

        .input-container>i:hover, a[role="button"]:hover {
          color: #777;
        }
        .amount-placeholder {
          font-size: 20px;
          height: 34px;
        }

        .amount-placeholder>button {
          float: right;
          width: 60px;
        }

        .amount-placeholder>span {
          line-height: 34px;
        }

        .card-row {
          text-align: center;
          margin: 20px 25px 10px;
        }

        .card-row span {
          width: 48px;
          height: 30px;
          margin-right: 3px;
          background-repeat: no-repeat;
          display: inline-block;
          background-size: contain;
        } 

        .cvc-preview-container {
          overflow: hidden;
        }

        .cvc-preview-container.two-card div {
          width: 48%;
          height: 80px;
        }
 

        .cvc-preview-container.two-card div.visa-mc-dis-cvc-preview {
          float: right;
        }

        .cvc-preview-container div {
          height: 160px;
        }
 
        .visa-mc-dis-cvc-preview {
          background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOYAAACOCAMAAAASE4S+AAAAAXNSR0IArs4c6QAAAadQTFRFAAAAzbFj+NyAyLNg+N2DzbRk+96CzrVj+96AzrNj+92By7Rl+92AzbRl/eCDzrRl/t+DzrVl/t+CzbVm/t+C3MFt3MFv/N2B/N6CzrRm/uCDzrRm/uCC7M93/N6CAAAAAQEBAgIBBAQCBQUDBwcECQgFDAsGDg0HEA4IEQ8JFRMLFxUMIBwQIR0RJSETKyYWLikYLyoYMCsZMSsZNC4bNzEcOTIdQDkhQTkhQzsiRT0jRj4kSkEmTEMnWE4tWU8uWk8uXFEvXVIwXlMwX1QxaV02bWA4bmE5cWQ6eGo+eWs+fW5Afm9Bi3pHjHtIkH9KmIZOmYdPnIlQnYpRo5BUppJVqJRWqpZXq5dYrJdYrZhZuaNfvaZhvqdiwKljwapjxK1lybFnyrJoy7NozrVm1Ltq171u2L5v2b9s2b9t2sBt3cNy3zEx3zIx38Rz4MVz4cZ04kI552NI6GVJ6Mx36s5368957dF674xb79J78NN78dV78tV789Z99Nd99dh+9rZv9th+9tl+99l/+duA+sx5+sx6+t2B+92B/N6B/d+C/uCD////AikOogAAAB90Uk5TACQkJSU9PT4+Q0NERJqav7/AwNjY4uLi4u7u8/P6+u6knPAAAAJkSURBVHja7d3pTxNBGMfxQbwAW06Pcj0tntQT8b7v+0JFxaserQcuKlQUFbFUaqvjH+1uG0lMfEETie4z39+bJ/tik/1kjt3MbDLGBFkWbeu0CtPZFq03v7KwxSpO04KKcmm7VZ32xeW2VK70nUF7tlj1afJnH+tA6k3UBWbUrHKBudJ0u8DsNtaJwIQJEyZMmDBhwoQJEyZMmPPCFCcCEyZMmDBhwoQJEyZMmDBhwoQJEyZMmDBhwoQJ010m+5swYcKECRMmTJgwYcKECRMmTJgwYcIMFfPHP8/vz5PLjnpzzmg2F07mxIhXVUYmwsjMVan0nbkQMrOeN1aY+zAsjHleNoRMf1x+rWa6KfjjM4RMvxdWN63+4QaYMGHC/EvM6b0HgpI6tvtoyq9vz+4/clcf89sJ2eiXIZGEyJD9sEl6RAa1MVPbpczsk8vT52SHHZCDUxelXxtzW/x4wPx+6cxn+0A2208PX9pB2aONef7xk3JrBjkth4Jysnf9fX1T0Czzmqx+6pcvcel/pJd5RRLXg1p6d0vWvdHKHJCeG2XljLVb5aZS5r2E3A6uTiUu2Km1klLK3CXxZDK5xd6RNYd3St+MTuaryr94G6y92iuy77X+b9rSi/d8usOEyZIXC5gsR7O5MN9bRexvwoQJEyZMmDBhwoQJEyZMmDBhwoT5vzNLLihLJu8CM2+6XGB2meUuMFeYyEf9ysmIqcsUtSuLmQZjGp8pdxafNxtjamIZ1f12MhOrDQ6uXhRLD4/nVb4/S/nx4XRsSeUY8prGtOI0186eKl8Xae3QSOxojTSUgT8BEvkXyqDHONgAAAAASUVORK5CYII=") center center/contain no-repeat;
        }

        .submit-button-lock {
          height: 20px;
          margin-top: -2px;
          margin-right: 7px;
          vertical-align: middle;
          background: url("data:image/png;base64,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NQxdFfpzGC4sNKwm7Hm4VXh+eHcELWJFREPEu0iPyNLIR4uNFksWd0bJR8VF1UdNRXtFl0VLl1gsWbPkRoxajCCmPRYfGxV7JHZyqffS3UuH4+ziCuPuLjNclrPs2nK15anLz66QX8FZcSoeGx8d3xD/iRPCqeVMrvRfuXflBNeTu4f7kufGK+eN8V34ZfyRBJeEsoTRRJfEXYljSa5JFUnjAk9BteB1sl/ygeSplJCUoykzqdGpzWmEtPi000IlYYqwK10zPSe9L8M0ozBDuspp1e5VE6JA0ZFMKHNZZruYjv5M9UiMJJslg1kLs2qy3mdHZZ/KUcwR5vTkmuRuyx3J88n7fjVmNXd1Z752/ob8wTXuaw6thdauXNu5Tnddwbrh9b7rj20gbUjZ8MtGy41lG99uit7UUaBRsL5gaLPv5sZCuUJR4b0tzlsObMVsFWzt3WazrWrblyJe0fViy+KK4k8l3JLr31l9V/ndzPaE7b2l9qX7d+B2CHfc3em681iZYlle2dCu4F2t5czyovK3u1fsvlZhW3FgD2mPZI+0MqiyvUqvakfVp+qk6oEaj5rmvep7t+2d2sfb17/fbX/TAY0DxQc+HhQcvH/I91BrrUFtxWHc4azDz+ui6rq/Z39ff0TtSPGRz0eFR6XHwo911TvU1zeoN5Q2wo2SxrHjccdv/eD1Q3sTq+lQM6O5+AQ4ITnx4sf4H++eDDzZeYp9qukn/Z/2ttBailqh1tzWibakNml7THvf6YDTnR3OHS0/m/989Iz2mZqzymdLz5HOFZybOZ93fvJCxoXxi4kXhzpXdD66tOTSna6wrt7LgZevXvG5cqnbvfv8VZerZ645XTt9nX297Yb9jdYeu56WX+x+aem172296XCz/ZbjrY6+BX3n+l37L972un3ljv+dGwOLBvruLr57/17cPel93v3RB6kPXj/Mejj9aP1j7OOiJwpPKp6qP6391fjXZqm99Oyg12DPs4hnj4a4Qy//lfmvT8MFz6nPK0a0RupHrUfPjPmM3Xqx9MXwy4yX0+OFvyn+tveV0auffnf7vWdiycTwa9HrmT9K3qi+OfrW9m3nZOjk03dp76anit6rvj/2gf2h+2P0x5Hp7E/4T5WfjT93fAn88ngmbWbm3/eE8/syOll+AAAAYFBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////98JRy6AAAAH3RSTlMAAgYMEyIzOUpTVFViY3N2gJmcnaipq7fX3ebx+Pn8eTEuDQAAAI9JREFUKM/N0UkOglAQRdFHDyK90n64+9+lAyQgookjuaNKTlJJpaQlO2n6sW8SW/uCjrku2EloWDLhi3gDa4O3pTtA5Tt+BXDbiDsBmSQpAyZ3pRhoLUmS1QLxSilQPOcCSFfKgfxgPgfZ9ch7Y21LCcdd5wVH5SckEzkXc0ylpPJnMpETmX/d9eUpH1/5AKrsQVrz7YPBAAAAAElFTkSuQmCC") center center/contain no-repeat;
          width: 14px;
          display: inline-block;
        }

        .align-middle {
          vertical-align: middle;
        }

        
    </style>
@stop

