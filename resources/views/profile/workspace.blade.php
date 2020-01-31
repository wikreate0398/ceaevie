@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
        <span
            class="page-title-icon bg-gradient-danger text-white mr-2">
          {!! $menu["icon"] !!}
        </span> {{ $menu["name_$lang"] }} </h3>
    </div>
    
    @include('profile.utils.cards')
    
    @if(Auth::user()->type == 'user')
        <!--Modal-->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h2>Добавить новый QR код</h2>
                        <form class="forms-sample ajax__submit" action="{{ route('add_qr', ['lang' => $lang]) }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="visitCardMessageInput1">Подпись на визитке <span class="req">*</span></label>
                                <input type="text" class="form-control"
                                       id="visitCardMessageInput1"
                                       name="card_signature" 
                                       placeholder="Спасибо, что нас посетили" value="">
                            </div>
                            <div class="form-group">
                                <label
                                    for="companyNameInput1">Название заведения <span class="req">*</span></label>
                                <input type="text" class="form-control"
                                       id="companyNameInput1"
                                       name="institution_name" 
                                       placeholder="Введите название заведения">
                            </div>
                            
                            <div class="form-group"> 
                                 <label>Выберите цвет подложки <span class="req">*</span></label>

                                <div style="margin-top: 5px;">

                                    @foreach($backgrounds as $background)
                                        <label class="radio">
                                            <input type="radio" name="background" value="{{ $background->id }}" checked>
                                            <span style="background-color: {{ $background->color }}"></span>
                                        </label>
                                    @endforeach
                                     
                                </div>
                            </div>
                            
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-gradient-info mr-2">Сохранить</button>
                                <button class="btn btn-light" type="button" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        @if($qr->count() < 3)
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-gradient-info btn-rounded btn-block" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i>
                         Добавить QR-код</button>
                </div>
            </div>
        @endif
    @endif

    @if($qr->count())
        <div class="row" style="margin-top: 40px;">
            @foreach($qr as $item)
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card new-qr created">
                        <div class="card-body">
                            <div class="created-qr" style="background-color:{{ $item->background->color }};">
                                @php
                                    $logo = $item->background->logo ? '/uploads/backgrounds/' . $item->background->logo : asset('profile_theme') . '/assets/images/logo.png';
                                @endphp

                                <img src="{{ $logo }}" alt="logo">
                                <img src="/public/uploads/qr_codes/{{ $item->qr_code }}" class="qr-code-img" alt="qr-code">

                                <h5 style="color: {{ $item->background->font_color }};">
                                    {{ Auth::user()->name }} {{ Auth::user()->lastname }}
                                </h5>
                                
                                <h2  style="color: {{ $item->background->code_color }};">
                                    {{ $item->code }}
                                </h2>
                            </div>
                            <p class="medium">{{ $item->card_signature }}</p>
                            <span class="title_1">{{ $item->institution_name }}</span>
                            <p>Чтобы оставить чаевые, наведите камеру на QR-код или введите код получателя на
                                <a href="{{ getAppUrl('pay') }}" target="blank">pay.{{ config('app.base_domain') }}</a></p>
                            <div class="payment">
                                @foreach($payments as $payment)
                                    <img src="/uploads/payment_types/{{ $payment->image_black_white }}" style="max-height: 17px;">
                                @endforeach 
                            </div>
                            <div class="action">
                                <div> 
                                    <img src="{{ asset('profile_theme') }}/assets/images/print.png" 
                                         alt="print"
                                         onclick="window.location='{{ route('qr_to_pdf', ['lang' => $lang, 'id' => $item->id]) }}'">
                                    <a href="{{ route('payment', ['lang' => $lang, 'code' => $item->code]) }}" target="_blank">
                                        <i class="fa fa-external-link" 
                                           aria-hidden="true" 
                                           style="position: relative; font-size: 19px; font-weight: bold; top: 2px; padding-left: 5px;"></i> 
                                    </a>
                                </div>
                                <a href="{{ route('delete_qr', ['lang' => $lang, 'id' => $item->id]) }}" class="confirm_link" data-confirm="Вы действительно желаете удалить?">
                                    <img src="{{ asset('profile_theme') }}/assets/images/trash.png" alt="trash-icon">
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@stop

