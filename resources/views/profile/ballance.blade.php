@extends('layouts.personal_profile')

@section('content')
    <script>
        var pageUrl = '{{ route('ballance', ['lang' => $lang]) }}';
    </script>
    <div class="page-header">
        <h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      {!! $menu["icon"] !!}
    </span> {{ $menu["name_$lang"] }} </h3>
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
        @if($total_amount >= setting('minimum_withdrawal') && $bank_cards->count() > 0 && Auth::user()->verification_status == 'confirm')
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
                        @if(Auth::user()->verification_status != 'confirm')
                            Длы вывода средств необходимо пройти верификацию в разделе &nbsp;
                            <a href="{{ route('account', ['lang' => $lang]) }}">
                                Мой профиль
                            </a> 
                        @elseif($total_amount < setting('minimum_withdrawal'))
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
                            @if(@$card->card_type->image)
                                <div class="card-type">
                                    <img
                                        src="{{ asset('uploads') }}/card_types/{{ @$card->card_type->image }}"
                                        alt="card-type"
                                        style="max-width: 50px;">
                                    <!-- <img
                                        src="{{ asset('profile_theme') }}/assets/images/socials/sberbank.png"
                                        alt="bank"> -->
                                </div>
                            @endif
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
                        
                        <div class="form-group col-md-12">
                            <label for="ExpiryDate">Срок действия
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="ExpiryDate"
                                   placeholder="11/19"
                                   name="expiry_date">
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
                                       autocomplete="off" 
                                       placeholder="Сумма"
                                       name="price"
                                       onkeyup="setMoney(this);">
                                       <small class="text-danger">
                                            Минимальная сумма для вывода: <b>{{ setting('minimum_withdrawal') }} руб.</b> 
                                            <br>
                                            Комиссия <b>-{{ setting('commision_withdrawal') }} руб.</b>
                                        </small>
                                        <br>
                                       <small class="total-withdraw" style="display: none; color: rgb(133, 181, 82)">Итог: <span></span> руб.</small>
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

                            @if(\Auth::user()->special_payout)
                                <div class="form-group col-md-12"> 
                                    <label>Сервис</label>
                                    <select name="payout_branch" class="form-control">
                                        <option value="common" selected>Общий</option>
                                        <option value="special">Специальный</option>
                                    </select> 
                                </div>  
                            @endif
                            
                            <div style="text-align: center" class="col-md-12">
                                <button type="submit"
                                        class="btn btn-gradient-info mr-2">Вывести
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <hr>
    
    @if($withdraws->count() or (request()->from or request()->to or request()->rand))
    <form action="{{ route('ballance', ['lang' => $lang]) }}">
        <div class="row">
            <div class="col-md-12">
                <h3 class="title-section">История вывода средств</h3>
                <h4 class="title">Отобразить вывод средств за период</h4>
            </div>
            
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="дд.мм.гг"
                           name="from"
                           value="{{ request()->from }}"
                           autocomplete="off">
                    <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                         alt="calendar">
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="дд.мм.гг"
                           name="to"
                           value="{{ request()->to }}"
                           autocomplete="off">
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
                    <select class="form-control form-control-lg per-page"
                            id="itemsPerPageSelect" onchange="window.location= pageUrl + '?per_page=' + this.value">
                        <option value="10" {{ (session()->get('ballance_per_page') == 10) ? 'selected' : '' }}>10</option>
                        <option value="20" {{ (session()->get('ballance_per_page') == 20) ? 'selected' : '' }}>20</option>
                        <option value="50" {{ (session()->get('ballance_per_page') == 50) ? 'selected' : '' }}>50</option>
                    </select>
                    
                    <a href="{{ route('ballance', ['lang' => $lang, 'period' => 'whole']) }}" class="sort-transaction {{ (request()->period == 'whole') ? 'active-sort' : '' }}">За весь
                        период
                    </a>
                    <a href="{{ route('ballance', ['lang' => $lang, 'period' => 'week']) }}" class="sort-transaction {{ (request()->period == 'week') ? 'active-sort' : '' }}">За
                        неделю
                    </a>
                    <a href="{{ route('ballance', ['lang' => $lang, 'period' => 'month']) }}" class="sort-transaction {{ (request()->period == 'month') ? 'active-sort' : '' }}">За
                        месяц
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="justify-content: space-between; display: flex;">
                    <input type="text" class="form-control"
                           id="rand" 
                           placeholder="Поиск по номеру транзакции"
                           value="{{ request()->rand }}" 
                           autocomplete="off" />
                    <button type="button" 
                            class="btn btn-gradient-info" style="margin-left: 10px;"
                            onclick="window.location= pageUrl + '?rand=' + getElementById('rand').value">
                            <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>

    @if(request()->from or request()->to or request()->rand)
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('ballance', ['lang' => $lang]) }}" class="btn btn-gradient-danger">Сбросить</a>
            </div>
        </div>
    @endif
    
    <div class="row">
        @if($withdraws->count())
            <div class="col-md-12 grid-margin table-history">
                <table class="history">
                    <thead>
                    <tr>
                        <td>Номер транзакции</td>
                        <td>Дата зачисления <!-- <i
                            class="mdi mdi-chevron-down"></i> --></td>
                        <td>Сумма <!-- <i class="mdi mdi-chevron-down"></i> -->
                        </td>
                        <td>Номер карты<!--  <i
                            class="mdi mdi-chevron-down"></i> --></td>
                        <td>Статус<!--  <i class="mdi mdi-chevron-down"></i> -->
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($withdraws as $withdraw)
                            <tr>
                                <td>{{ $withdraw->rand }}</td>
                                <td>{{ $withdraw->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $withdraw->amount }} Р</td>
                                <td class="code">{{ $withdraw->card->hide_number }}</td>
                                <td>
                                    @if($withdraw->status)
                                        {{ $withdraw->statusData->name_ru }}
                                    @else
                                        {{ $withdraw->requestStatusData->name_ru }}
                                    @endif 
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 align-center"> 
                <span>Показано  {{ $withdraws->count() }} из {{ $withdraws->total() }}</span>
            </div>
            <div class="col-md-6">  
                {{ $withdraws->appends(request()->input())->links() }} 
            </div>
        @else 
            <div class="col-12" style="margin-top: 20px;">
                <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
                  <p>Нет истории вывода</p>  
                </span>
            </div>
        @endif
    </div> 
    @else 
        <div class="row">
            <div class="col-12">
                <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
                  <p>Нет истории вывода</p>  
                </span>
            </div>
        </div>
    @endif
@stop

