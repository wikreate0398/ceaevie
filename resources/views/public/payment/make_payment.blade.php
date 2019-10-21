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

                            <div class="col-12  text-center make-payment-btn" style="margin-top: 50px; display: none;">
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
                            if($('#payment_type').val() && $('#priceInput').val()){
                                //$('#make-payment-form').submit();
                                $('.make-payment-btn').show();
                            }else{
                                $('.make-payment-btn').hide();
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
@stop

