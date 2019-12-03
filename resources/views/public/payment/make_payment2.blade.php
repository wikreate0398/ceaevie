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
                   
                  <script>
                    /**
                     https://developers.google.com/pay/api/web/guides/tutorial#apiversion
                     */
                    const baseRequest = {
                      apiVersion: 2,
                      apiVersionMinor: 0
                    }; 
                    const allowedCardNetworks = ["AMEX", "DISCOVER", "INTERAC", "JCB", "MASTERCARD", "VISA"]; 

                    const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];
 
                    const tokenizationSpecification = {
                      type: 'PAYMENT_GATEWAY',
                      parameters: {
                        'gateway': 'rbkmoney',
                        'gatewayMerchantId': 'rbkmoney-test'
                      }
                    };
 
                    const baseCardPaymentMethod = {
                      type: 'CARD',
                      parameters: {
                        allowedAuthMethods: allowedCardAuthMethods,
                        allowedCardNetworks: allowedCardNetworks
                      }
                    };
 
                    const cardPaymentMethod = Object.assign(
                      {},
                      baseCardPaymentMethod,
                      {
                        tokenizationSpecification: tokenizationSpecification
                      }
                    );
 
                    let paymentsClient = null;
 
                    function getGoogleIsReadyToPayRequest() {
                      return Object.assign(
                          {},
                          baseRequest,
                          {
                            allowedPaymentMethods: [baseCardPaymentMethod]
                          }
                      );
                    }
 
                    function getGooglePaymentDataRequest() {
                      const paymentDataRequest = Object.assign({}, baseRequest);
                      paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
                      paymentDataRequest.transactionInfo = getGoogleTransactionInfo();
                      paymentDataRequest.merchantInfo = {
                        // @todo a merchant ID is available for a production environment after approval by Google
                        // See {@link https://developers.google.com/pay/api/web/guides/test-and-deploy/integration-checklist|Integration checklist}
                        // merchantId: '01234567890123456789',
                        merchantName: 'Example Merchant'
                      };
                      return paymentDataRequest;
                    }
 
                    function getGooglePaymentsClient() {
                      if ( paymentsClient === null ) {
                        paymentsClient = new google.payments.api.PaymentsClient({environment: 'TEST'});
                      }
                      return paymentsClient;
                    }
 
                    function onGooglePayLoaded() {
                      const paymentsClient = getGooglePaymentsClient();
                      paymentsClient.isReadyToPay(getGoogleIsReadyToPayRequest())
                          .then(function(response) {
                            if (response.result) {
                              // addGooglePayButton();
                              // @todo prefetch payment data to improve performance after confirming site functionality
                              // prefetchGooglePaymentData();
                            }
                          })
                          .catch(function(err) {
                            $('#google_pay_btn').hide();
                            // show error in developer console for debugging
                            console.error(err);
                          });
                    }
 
                    function addGooglePayButton() {
                      const paymentsClient = getGooglePaymentsClient();
                      const button =
                          paymentsClient.createButton({onClick: onGooglePaymentButtonClicked});
                      document.getElementById('container').appendChild(button);
                    }
 
                    function getGoogleTransactionInfo() {
                      return {
                        countryCode: 'RU',
                        currencyCode: 'RUB',
                        totalPriceStatus: 'FINAL', 
                        totalPrice: $('#priceInput').val()
                      };
                    }
 
                    function prefetchGooglePaymentData() {
                      const paymentDataRequest = getGooglePaymentDataRequest();
                      // transactionInfo must be set but does not affect cache
                      paymentDataRequest.transactionInfo = {
                        totalPriceStatus: 'NOT_CURRENTLY_KNOWN',
                        currencyCode: 'USD'
                      };
                      const paymentsClient = getGooglePaymentsClient();
                      paymentsClient.prefetchPaymentData(paymentDataRequest);
                    }

                    /**
                     * Show Google Pay payment sheet when Google Pay payment button is clicked
                     */
                    function onGooglePaymentButtonClicked() {
                      const paymentDataRequest = getGooglePaymentDataRequest();
                      paymentDataRequest.transactionInfo = getGoogleTransactionInfo();

                      const paymentsClient = getGooglePaymentsClient();
                      paymentsClient.loadPaymentData(paymentDataRequest)
                          .then(function(paymentData) { 
                            // handle the response
                            // processPayment(paymentData);
                          })
                          .catch(function(err) {
                            // show error in developer console for debugging
                            console.error(err);
                          });
                    }

                    /**
                     * Process payment data returned by the Google Pay API
                     *
                     * @param {object} paymentData response from Google Pay API after user approves payment
                     * @see {@link https://developers.google.com/pay/api/web/reference/object#PaymentData|PaymentData object reference}
                     */
                    function processPayment(paymentData) { 

                      $('#google_pay_input').val(JSON.stringify(paymentData));
                      $('#make-payment-form').submit();

                      // @todo pass payment token to your gateway to process payment
                      //paymentToken = paymentData.paymentMethodData.tokenizationData.token;
                    }
              </script>
              <!-- <script async src="https://pay.google.com/gp/p/js/pay.js" onload="onGooglePayLoaded()"></script> -->


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
                                            <input type="hidden" name="google_pay" id="google_pay_input">
                                            @foreach($payments as $payment)
                                              @php
                                                $id = '';
                                                $onclick="return false;";
                                                if($payment->id == '2'){
                                                  $id = 'google_pay_btn'; 
                                                }
                                              @endphp
                                              <button type="button" 
                                                      id="{{ $id }}" 
                                                      class="btn btn-white" 
                                                      onclick="setPaymentType(this, {{ $payment->id }})" -->
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
                                              <div class="col-12">
                                                  <div class="expiry-date-group form-group">
                                                      <label for="ExpiryDate">Срок действия <span class="req">*</span></label>
                                                      <input id="ExpiryDate" name="card[expiry_date]" class="form-control" type="text" placeholder="MM / YY" maxlength="7"> 
                                                  </div>
                                              </div> 
                                          </div> 
                                      </div>
                                  </div>  
                              </div>

                              <div class="row make-payment-btn text-center" style="margin-top: 50px; display: none;">
                                  <div class="col-md-5">
                                      <button type="button" class="btn btn-blue btn-v2 btn-back" onclick="toggleBlocks('.card-info, .make-payment-btn', '.payment-info');">Назад</button> 

                                     <!--  <button type="button" class="btn btn-blue btn-v2 btn-next" onclick="toggleBlocks('.btn-next, .payment-info', '.btn-back, .card-info, .btn-pay');">Далее</button>  -->
                                  </div>
                                  <div class="col-md-7 btn-pay">
                                      <button type="submit" class="btn btn-blue">Оплатить</button>
                                  </div>
                              </div>  
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

                          if (idPayment == 2) {
                            onGooglePaymentButtonClicked(); 
                          }else{
                            checkPaymentForm();
                          }
                        }

                        function setPrice(price) {
                          document.getElementById("priceInput").value = price; 
                        }

                        function checkPaymentForm(){
                          var idPayment = $('#payment_type').val();

                          if (idPayment == 1) {
                            toggleBlocks('.payment-info', '.card-info, .make-payment-btn');
                          }

                          // if($('#payment_type').val() && $('#priceInput').val()){  
                          //     //$('#make-payment-form').submit();
                          //     $('.make-payment-btn').show();
                          //     toggleBlocks('.btn-back, .btn-pay', '.btn-next');
                          // }else{
                          //   if(!$('#priceInput').val()){

                          //     $('.make-payment-btn').hide();
                          //   }
                          // } 
                        } 
                    </script>
                </div>
            </div>
        </div>
    </section> 
@stop

