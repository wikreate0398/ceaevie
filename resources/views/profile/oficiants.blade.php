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

	<div class="modal fade" id="addOficiant" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h2>Добавить Официанта</h2>
                    <form class="forms-sample ajax__submit" action="{{ route('add_oficiant', ['lang' => $lang]) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label >Имя <span class="req">*</span></label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>

                        <div class="form-group">
                            <label >Фамилия <span class="req">*</span></label>
                            <input type="text" class="form-control" name="lastname" value="">
                        </div>

                        <div class="form-group">
                            <label>Подпись на визитке <span class="req">*</span></label>
                            <input type="text" class="form-control" 
                                   name="card_signature" 
                                   placeholder="Спасибо, что нас посетили" value="">
                        </div>

                        <div class="form-group">
                            <label >E-mail <span class="req">*</span></label>
                            <input type="text" class="form-control" required name="email" value="">
                        </div>

                        <div class="form-group">
                            <label >Телефон </label>
                            <input type="text" class="form-control" name="phone" value="">
                        </div>
                        
                        <div style="text-align: center">
                            <button type="submit" class="btn btn-gradient-info mr-2">Добавить</button>
                            <button class="btn btn-light" type="button" data-dismiss="modal">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attr" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h2>Пригласить Официанта</h2>
                    <form class="forms-sample ajax__submit" action="{{ route('invite_oficiant', ['lang' => $lang]) }}">
                        {{ csrf_field() }}
                         
                        <div class="form-group">
                            <label>E-mail <span class="req">*</span></label>
                            <input type="text" class="form-control" required name="email" value="">
                        </div> 

                        <div class="form-group">
                            <label>Подпись на визитке <span class="req">*</span></label>
                            <input type="text" class="form-control" 
                                   name="card_signature" 
                                   placeholder="Спасибо, что нас посетили" value="">
                        </div>
                        
                        <div style="text-align: center">
                            <button type="submit" class="btn btn-gradient-info mr-2">Пригласить</button>
                            <button class="btn btn-light" type="button" data-dismiss="modal">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-md-12">
            <button class="btn btn-gradient-info btn-rounded" data-toggle="modal" data-target="#addOficiant"><i class="fa fa-plus" aria-hidden="true"></i>
            	Добавить Официанта
            </button>  
            &nbsp;
            &nbsp;
        	<button class="btn btn-outline-info btn-rounded" data-toggle="modal" data-target="#attr"> 
            	Пригласить Официанта
            </button>
        </div>
    </div>
	
	@if($users->count())
	    <div class="row" style="margin-top: 30px;">  
	        <div class="col-md-12 grid-margin table-history">
	            <table class="history eq-table-cell">
	                <thead>
	                    <tr>
	                        <td>Фио</td>
	                        <td>E-mail</td>
	                        <td>Телфон</td>
	                        <td>Статус</td>
	                        <td>Дата привязки</td>  
                            @if(Auth::user()->work_type == 'common_sum')
                                <td>Отправить средства</td>  
                            @endif
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($users as $user)
	                        <tr>
	                            <td>{{ @$user->user->name }} {{ $user->user->lastname }}</td>
	                            <td>{{ $user->user->email }}</td>
	                            <td>{{ $user->user->phone }}</td>
	                            <td>
	                            	@if($user->status == 'pending')
	                            		В режиме ожидания
	                            	@elseif($user->status == 'rejected')
	                            		Отклонил
	                            	@else
										Подтвердил
	                            	@endif
	                            </td>
	                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                @if(Auth::user()->work_type == 'common_sum')
                                    <td align="center">
                                        <button class="btn btn-info btn-xs" 
                                                data-toggle="modal" 
                                                data-target="#myModal"
                                                onclick="$('#id_user').val({{ $user->id_user }})">
                                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        </button>
                                    </td>  
                                @endif
	                        </tr>
	                    @endforeach
	                </tbody>
	            </table>
	        </div>  
	    </div>
    @else 
	    <div class="row" style="margin-top: 30px;">
	    	<div class="col-12" style="margin-top: 20px;">
		        <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
		          <p>Нет привязанных официантов</p>  
		        </span>
		    </div>
	    </div>
    @endif

    @if(Auth::user()->work_type == 'common_sum')
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
                        <h2>Отправить средства</h2>
                        <form class="forms-sample row ajax__submit" action="{{ route('request_money', ['lang' => $lang]) }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="id_user" id="id_user">

                            <div class="form-group col-md-12">
                                <label>Укажите сумму <span class="req">*</span></label>
                                <input type="text" required class="form-control price-mask" name="amount">       
                            </div> 
      
                            <div style="text-align: center" class="col-md-12">
                                <button type="submit" class="btn btn-gradient-info mr-2">Отправить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

