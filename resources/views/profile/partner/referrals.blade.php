@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
		<h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      {!! $menu["icon"] !!}
    </span> {{ $menu["name_$lang"] }} </h3>

    <span><b>Ваш Код:</b> {{ \Auth::user()->code }}</span>
	</div>

	@include('profile.utils.cards')
   
	@if($referrals->count())
	    <div class="row" style="margin-top: 30px;">  
	        <div class="col-md-12 grid-margin table-history">
	            <table class="history eq-table-cell">
	                <thead>
	                    <tr>
	                        <td class="ac" style="33.3%">Фио</td>
	                        <td class="ac" style="33.3%">E-mail</td>
	                        <td class="ac" style="33.3%">Телефон</td>
	                    </tr>
	                </thead>
	                <tbody>
	                    @foreach($referrals as $user)
	                        <tr>
	                            <td class="ac">{{ @$user->name }} {{ $user->lastname }}</td>
	                            <td class="ac">{{ $user->email }}</td>
	                            <td class="ac">{{ $user->phone }}</td>
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
		          <p>Нет рефералов</p>  
		        </span>
		    </div>
	    </div>
    @endif 
 
@stop

