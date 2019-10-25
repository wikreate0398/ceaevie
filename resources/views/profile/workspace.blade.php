@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      <i class="mdi mdi-home"></i>
    </span> Рабочая область </h3>
    </div>

    @include('profile.utils.cards')

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

    @if($qr->count())
        <div class="row" style="margin-top: 40px;">
            @foreach($qr as $item)
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card new-qr created">
                        <div class="card-body">
                            <div class="created-qr" style="background-color:{{ $item->background->color }};">
                                <img src="{{ asset('profile_theme') }}/assets/images/logo.png" alt="logo">
                                <img src="/public/uploads/qr_codes/{{ $item->qr_code }}" class="qr-code-img" alt="qr-code">
                                 
                                <h5>{{ Auth::user()->name }} {{ Auth::user()->lastname }}</h5>
                                <h2>{{ $item->code }}</h2>
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
                                <img src="{{ asset('profile_theme') }}/assets/images/print.png" alt="print">
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

