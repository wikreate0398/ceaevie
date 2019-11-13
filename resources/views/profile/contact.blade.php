@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
      <h3 class="page-title">
      <span
          class="page-title-icon bg-gradient-danger text-white mr-2">
        {!! $menu["icon"] !!}
      </span> {{ $menu["name_$lang"] }} </h3>
    </div>
    <div class="row">
        <div class="col-md-8 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample row ajax__submit" action="{{ route('send_contact_us', ['lang' => $lang]) }}">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6">
                            <label for="nameInput2">Ваше имя <span class="req">*</span></label>
                            <input type="text" class="form-control"
                                   id="nameInput2"
                                   name="name" 
                                   value="{{ Auth::user()->name }}" 
                                   placeholder="Ваше имя">
                        </div>
                        <div class="form-group col-md-6">
                            <label
                                for="phoneInput2">Телефон <span class="req">*</span></label>
                            <input type="text" class="form-control"
                                   id="phoneInput2"
                                   name="phone" 
                                   value="{{ Auth::user()->phone }}" 
                                   placeholder="Фамилия">
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label for="messageInput1">Текст вопроса или предложения <span class="req">*</span></label>
                            <input type="text" class="form-control"
                                   id="messageInput1"
                                   name="message" 
                                   placeholder="Спасибо, за то что посетили нас">
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <button type="submit" class="btn btn-gradient-info btn-rounded btn-block">
                                Отправить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

