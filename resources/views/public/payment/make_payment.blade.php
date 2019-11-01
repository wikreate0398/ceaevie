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

                                    <div class="col-12 text-center rating-inner">
                                      <select class="rating-stars" id="rating" name="rating" autocomplete="off">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                      </select>
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
                            
                            @if(false)
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
                                  <!-- <div class="col-md-5">
                                      <button type="button" class="btn btn-blue btn-v2 btn-back" onclick="toggleBlocks('.card-info, .btn-pay, .btn-back', '.btn-next, .payment-info');">Назад</button> 

                                      <button type="button" class="btn btn-blue btn-v2 btn-next" onclick="toggleBlocks('.btn-next, .payment-info', '.btn-back, .card-info, .btn-pay');">Далее</button> 
                                  </div> -->
                                  <div class="col-md-7 btn-pay">
                                      <button type="submit" class="btn btn-blue">Оплатить</button>
                                  </div>
                              </div> 
                            @endif
                    </form>

                    <script>
                        function setPaymentType(button, idPayment) { 
                          if (!$('#priceInput').val()) {
                            alert('Укажите сумму');
                            return;
                          } 

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
                              $('#make-payment-form').submit();
                              //$('.make-payment-btn').show();
                              //toggleBlocks('.btn-back, .btn-pay', '.btn-next');
                          }else{
                            if(!$('#priceInput').val()){

                              //$('.make-payment-btn').hide();
                            }
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

