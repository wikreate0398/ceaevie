@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      <i class="mdi mdi-currency-usd"></i>
    </span> Ваш баланс</h3>
    </div>
    <div class="row">
        <div class="col-md-4 grid-margin">
            <div
                class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <h4 class="font-weight-normal mb-3">
                        Текущий баланс для вывода
                    </h4>
                    <h2>{{ $total_amount }} P <span> / {{ setting('minimum_withdrawal') }} P</span></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if($total_amount >= setting('minimum_withdrawal') && $bank_cards->count() > 0)
        <div class="col-md-4 grid-margin">
            <button type="submit"
                    class="btn btn-gradient-info btn-rounded btn-block"
                    style="min-height: 55px"
                    data-toggle="modal"
                    data-target="#withdrawalFfunds">
                Заказать вывод средств
            </button>
        </div>
        @else
            <div class="col-12">
                <span class="d-flex align-items-center purchase-popup alert-warning" style="justify-content: space-between;">
                    <p style="color: #fff;">
                        @if($total_amount < setting('minimum_withdrawal'))
                            Длы вывода средств необходимо иметь на счету не менее {{ setting('minimum_withdrawal') }} P 
                        @else
                            Длы вывода средств необходимо привязать банковскую карту
                        @endif
                    </p>  
                </span>
            </div>
        @endif
    </div>
    
    <hr>
    
    <div class="row">
        @if($bank_cards->count())
            @foreach($bank_cards as $card)
                <div class="col-md-4 grid-margin stretch-card">
                     
                    <div class="card card-added">
                        <div class="card-body">
                            <span class="name">{{ ucfirst($card->name) }}</span>
                            <div style="display: flex; justify-content: space-between; align-items: center;"> 
                                <span class="card-nr">
                                    {{ $card->hide_number }}
                                </span>
                                <span class="expiration-date">{{ $card->month }}/{{ $card->year }}</span>
                            </div>
                            <div class="card-type">
                                <img
                                    src="{{ asset('uploads') }}/card_types/{{ $card->card_type->image }}"
                                    alt="card-type"
                                    style="max-width: 50px;">
                                <!-- <img
                                    src="{{ asset('profile_theme') }}/assets/images/socials/sberbank.png"
                                    alt="bank"> -->
                            </div>
                           <a href="{{ route('delete_card', ['lang' => $lang, 'id' => $card->id]) }}" class="confirm_link" data-confirm="Вы действительно желаете удалить?">
                                <img src="{{ asset('profile_theme') }}/assets/images/trash.png" alt="delete">
                           </a> 
                        </div>
                    </div> 
                </div>
            @endforeach
        @endif
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card add-card">
                <div class="card-body align-center">
                    <div class="ellips" data-toggle="modal"
                         data-target="#myModal">+
                    </div>
                    <p>Привязать <br> банковскую карту</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <h2>Привязать карту</h2>
                    <form class="forms-sample row ajax__submit" action="{{ route('add_card', ['lang' => $lang]) }}">
                        {{ csrf_field() }}

                        <div class="form-group col-md-12">
                            <label for="ownerCardInput1">Укажите держателя
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="ownerCardInput1"
                                   placeholder="Имя фамилия на латинице"
                                   name="name">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="CreditCardNumber">Укажите номер
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="CreditCardNumber"
                                   placeholder="4276   ....   ....   ..96"
                                   name="number">
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="ExpiryDate">Срок действия
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="ExpiryDate"
                                   placeholder="11/19"
                                   name="expiry_date">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="SecurityCode">CSV*</label>
                            <input type="text" class="form-control"
                                   id="SecurityCode"
                                   placeholder="123"
                                   name="cvc">
                        </div>
                        
                        <div style="text-align: center" class="col-md-12">
                            <button type="submit"
                                    class="btn btn-gradient-info mr-2">Привязать
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($bank_cards->count() > 0)
        <div class="modal fade" id="withdrawalFfunds" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <h2>Вывод средств</h2>
                        <form class="forms-sample row ajax__submit" action="{{ route('withdraw_funds', ['lang' => $lang]) }}">
                            {{ csrf_field() }}

                            <div class="form-group col-md-12">
                                <label for="wth_price">Укажите сумму *</label>
                                <input type="text" class="form-control price-mask"
                                       id="wth_price"
                                       placeholder="Имя фамилия на латинице"
                                       name="price">
                            </div>

                            <div class="form-group col-md-12"> 
                                <label for="CreditCardNumber">Укажите карту *</label>
                                <select name="card" class="form-control">
                                    <option value="0">Выбрать</option>
                                    @foreach($bank_cards as $card)
                                        <option value="{{ $card->id }}">{{ $card->hide_number }}</option>
                                    @endforeach
                                </select> 
                            </div> 
                            
                            <div style="text-align: center" class="col-md-12">
                                <button type="submit"
                                        class="btn btn-gradient-info mr-2">Привязать
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <hr>
    
    <form action="">
        <div class="row">
            <div class="col-md-12">
                <h3 class="title-section">История вывода
                    средств</h3>
                <h4 class="title">Отобразить вывод средств за период</h4>
            </div>
            
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="mm/dd/yy">
                    <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                         alt="calendar">
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="mm/dd/yy">
                    <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                         alt="calendar">
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <button type="submit"
                        class="btn btn-gradient-info btn-rounded btn-block"
                        style="min-height: 55px">Показать
                </button>
            </div>
        </div>
    </form>
    
    <form action="">
        <div class="row">
            <div class="col-md-8 grid-margin">
                <div class="inline">
                    <label for="itemsPerPageSelect">Показывать
                        по</label>
                    <select
                        class="form-control form-control-lg per-page"
                        id="itemsPerPageSelect">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    
                    <a href="button" class="sort-transaction">
                        За весь
                        период
                    </a>
                    <a href="button" class="sort-transaction">
                        За
                        неделю
                    </a>
                    <a href="button" class="sort-transaction">
                        За
                        месяц
                    </a>
                </div>
            
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control"
                           placeholder="Поиск по номеру транзакции"/>
                </div>
            </div>
        </div>
    </form>
    
    <div class="row">
        <div class="col-md-12 grid-margin table-history">
            <table class="history">
                <thead>
                <tr>
                    <td>Номер транзакции</td>
                    <td>Дата зачисления <i
                        class="mdi mdi-chevron-down"></i></td>
                    <td>Сумма <i class="mdi mdi-chevron-down"></i>
                    </td>
                    <td>Номер карты <i
                        class="mdi mdi-chevron-down"></i></td>
                    <td>Статус <i class="mdi mdi-chevron-down"></i>
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>8451233</td>
                    <td>22.12.2019</td>
                    <td>100 Р</td>
                    <td class="code">4276 .... .... ..96</td>
                    <td>Не введен код авторизации</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6 align-center">
            <span>Показано 10 из 16</span>
        </div>
        <div class="col-md-6">
            <ul class="pages">
                <li>
                    <a href="#"><i class="mdi mdi-chevron-left"></i></a>
                </li>
                <li class="active"><a href="#1">1</a></li>
                <li><a href="#2">2</a></li>
                <li>
                    <a href="#"><i
                        class="mdi mdi-chevron-right"></i></a>
                </li>
            </ul>
        </div>
    </div> 
@stop

