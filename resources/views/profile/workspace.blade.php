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
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card new-qr">
                <div class="card-body">
                    <img src="{{ asset('profile_theme') }}/assets/images/logo.png" alt="logo">
                    <div class="ellips" data-toggle="modal" data-target="#myModal">+</div>
                    <span class="title_1">Создать новый QR</span>
                    <h5>Иван Смирнов</h5>
                    <h2>892-5</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card new-qr">
                <div class="card-body">
                    <img src="{{ asset('profile_theme') }}/assets/images/logo.png" alt="logo">
                    <div class="ellips" data-toggle="modal" data-target="#myModal">+</div>
                    <span class="title_1">Создать новый QR</span>
                    <h5>Иван Смирнов</h5>
                    <h2>892-5</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card new-qr created">
                <div class="card-body">
                    <div class="created-qr" style="background-color:#f6f6f6;">
                        <img src="{{ asset('profile_theme') }}/assets/images/logo.png" alt="logo">
                        <img src="{{ asset('profile_theme') }}/assets/images/dashboard/qr.png" alt="qr-code">
                        <h5>Иван Смирнов</h5>
                        <h2>892-5</h2>
                    </div>
                    <p class="medium">Спасибо, за чаевые</p>
                    <span class="title_1">Бар "Клевое место"</span>
                    <p>Чтобы оставить чаевые, наведите камеру на QR-код или введите код получателя на <a href="pay.чаевые-онлайн.рф">pay.чаевые-онлайн.рф</a></p>
                    <div class="payment">
                        <img src="{{ asset('profile_theme') }}/assets/images/dashboard/visa.png" alt="visa">
                        <img src="{{ asset('profile_theme') }}/assets/images/dashboard/google-pay.png" alt="google-pay">
                        <img src="{{ asset('profile_theme') }}/assets/images/dashboard/apple-pay.png" alt="apple-pay">
                    </div>
                    <div class="action">
                        <img src="{{ asset('profile_theme') }}/assets/images/print.png" alt="print">
                        <img src="{{ asset('profile_theme') }}/assets/images/trash.png" alt="trash-icon">
                    </div>
                </div>
            </div>
        </div>
      <!--   <div class="col-md-4 grid-margin stretch-card">
            <div class="card new-qr">
                <div class="card-body">
                    <img src="{{ asset('profile_theme') }}/assets/images/logo.png" alt="logo">
                    <div class="ellips" data-toggle="modal" data-target="#myModal">+</div>
                    <span class="title_1">Создать новый QR</span>
                    <h5>Иван Смирнов</h5>
                    <h2>892-5</h2>
                </div>
            </div>
        </div> -->
        
    </div>
@stop

