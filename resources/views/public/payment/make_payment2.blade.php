@extends('layouts.public')

@section('content')
<section class="pt-90 pb-90 pay-tip-page loader-v2-inner">
        <div class="flip-square-loader mx-auto"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                    <a href="{{ route('home') }}">
                      <img src="/img/logo.png" alt="logo"> 
                    </a>
                    <div class="profile-photo mt-50">
                    	<img src="{{ imageThumb($data->user->image, 'uploads/clients', 181, 181, 0) }}" alt="profile">
                    </div>
                    <h3 class="name">{{ $data->user->name }} <br>{{ $data->user->lastname }}</h3>
                    <p class="grey">{{ $data->card_signature }}</p>
                </div>
                <div class="col-md-6 offset-md-3">
                    
                    <form class="ajax__submit" id="make-payment-form" action="{{ route('make_payment2', ['lang' => $lang]) }}">
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

                                <div class="col-12 text-center rating-inner">
                                  <select class="rating-stars" id="rating" name="rating" autocomplete="off">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                  </select>

                                  <textarea name="review" 
                                            placeholder="Оставьте свой отзыв" 
                                            class="form-control rating-comment" 
                                            style="margin-top: 10px; display: none"></textarea>
                                </div>

                                <div class="col-12 text-center">  
                                  <div class="payment-sistems">
                                    <input type="hidden" name="payment" value="" id="payment_type"> 
                                    @if($data->user->payment_center)
                                      <button type="button" class="btn btn-white" onclick="setPaymentType(this, 'payment_center')">
                                          <img src="/img/visa_pay.png" style="max-width: 145px;">
                                      </button>
                                    @endif

                                    @if($data->user->rbk)
                                      <button type="button" class="btn btn-white" onclick="setPaymentType(this, 'rbk')">
                                        <img src="/img/apple_google_pay.png" style="max-width: 130px;">
                                      </button>
                                    @endif 
                                  </div>
                                </div>
                            </div>
                        </div> 
                    </form>  

                    <script src="https://checkout.rbk.money/checkout.js"></script>
 
                    <script> 
                      function setPaymentType(button, payment) { 
                        if (!$('#priceInput').val()) {
                          alert('Укажите сумму');
                          return;
                        } 

                        $('#payment_type').val(payment);

                        $('.active-payment-type').removeClass('active-payment-type');
                        $(button).addClass('active-payment-type'); 

                        $('#make-payment-form').submit();
                      }

                      function setPrice(price) {
                        document.getElementById("priceInput").value = price; 
                      }  
                    </script>

                    
                </div>
            </div>
        </div>
    </section> 
@stop

