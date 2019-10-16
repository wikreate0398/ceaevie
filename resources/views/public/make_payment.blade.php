@extends('layouts.public')

@section('content')
<section class="pt-90 pb-90 pay-tip-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center">
                    <a href="/"><img src="/img/logo.png" alt="logo"></a>
                    <div class="profile-photo mt-50">
                    	<img src="/img/profile.png" alt="profile">
                    </div>
                    <h3 class="name">Иван <br>Смирнов</h3>
                    <p class="grey">Спасибо, за то что посетили нас</p>
                </div>
                <div class="col-md-6 offset-md-3">
                    <form class="ajax__submit" action="">
                    	{{ csrf_field() }}
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 mb-30">
                                <input type="text" id="priceInput" required name="" class="form-control rounded-input" onfocus="this.placeholder = ''" onblur="this.placeholder = 'XXX-X'" placeholder="XXX-X">
                            </div>

                            <div class="prices">
                            	<button type="button" onclick="setPrice(50)">50 P</button>
                            	<button type="button" onclick="setPrice(100)">100 P</button>
                            	<button type="button" onclick="setPrice(500)">500 P</button>
                            	<button type="button" onclick="setPrice(1000)">1 000 P</button>
                            </div>

                            <script>
                            	function setPrice(price) {
                            		document.getElementById("priceInput").value = price;
                            	}
                            </script>

                            <div class="col-12 text-center">
                                <div class="payment-sistems">
                                    <button type="submit" class="btn btn-white visa"></button>
                                    <button type="submit" class="btn btn-white google-pay"></button>
                                    <button type="submit" class="btn btn-white apple-pay"></button>
                                 </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@stop

