@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12"> 
     <div class="portlet light bg-inverse">  
            <div class="portlet-body form">
             
 
            <form action="/{{ $method }}/{{ $data['id'] }}/update-admin-user" class="ajax__submit form-horizontal">  

                {{ csrf_field() }}
                
                <div class="form-body" style="padding-top: 20px;">
                    @include('admin.utils.input', ['label' => 'Имя', 'req' => true, 'name' => 'name', 'data' => $data])
                    @include('admin.utils.input', ['label' => 'E-mail', 'req' => true, 'name' => 'email', 'data' => $data])
                    @include('admin.utils.input', ['label' => 'Новый Пароль', 'name' => 'password', 'type' => 'password', 'data' => []])
                    @include('admin.utils.input', ['label' => 'Повторите Пароль', 'name' => 'repeat_password', 'type' => 'password'])
    
                </div>
                <div class="form-actions">
                    <div class="btn-set pull-left"> 
                        <button type="submit" class="btn green">Сохранить</button>
                    </div> 
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
 
@stop