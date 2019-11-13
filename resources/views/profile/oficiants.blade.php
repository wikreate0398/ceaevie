@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
		<h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      {!! $menu["icon"] !!}
    </span> {{ $menu["name_$lang"] }} </h3>
	</div>

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
                            <labe >Имя <span class="req">*</span></label>
                            <input type="text" class="form-control" name="name" value="">
                        </div>

                        <div class="form-group">
                            <labe >Фамилия <span class="req">*</span></label>
                            <input type="text" class="form-control" name="lastname" value="">
                        </div>

                        <div class="form-group">
                            <labe >E-mail <span class="req">*</span></label>
                            <input type="text" class="form-control" required name="email" value="">
                        </div>

                        <div class="form-group">
                            <labe >Телефон </label>
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
                            <labe >E-mail <span class="req">*</span></label>
                            <input type="text" class="form-control" required name="email" value="">
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
	            <table class="history">
	                <thead>
	                    <tr>
	                        <td style="width: 20%;">Фио</td>
	                        <td style="width: 20%;">E-mail</td>
	                        <td style="width: 20%;">Телфон</td>
	                        <td style="width: 20%;">Статус</td>
	                        <td style="width: 20%;">Дата привязки</td>  
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($users as $user)
	                        <tr>
	                            <td>{{ $user->user->name }} {{ $user->user->lastname }}</td>
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
@stop

