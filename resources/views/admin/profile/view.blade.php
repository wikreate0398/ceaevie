@extends('layouts.admin')

@section('content') 
    <div class="row">
        @if(Auth::user()->type == 'admin')
            <div class="col-md-12">
                
                <button class="btn btn-primary btn-sm" onclick="$('.hide__container').slideToggle();" style="margin-bottom: 20px;">
                    <i class="fa fa-user" aria-hidden="true"></i> Добавить пользователя
                </button>

                <div class="portlet light bordered hide__container" style="display: none;">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject">Добавить пользователя</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form action="/{{ config('admin.path') }}/profile/addNewUser" method="POST" class="form-horizontal ajax__submit">

                            {{ csrf_field() }}

                            <div class="form-body">
                                <div class="form-group">
                                    <label for="" class="col-lg-2 col-sm-2 control-label">Имя</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" name="name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-lg-2 col-sm-2 control-label">E-mail</label>
                                    <div class="col-lg-10">
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="" class="col-lg-2 col-sm-2 control-label">Тип</label>
                                    <div class="col-lg-10">
                                        <select name="type" class="form-control">
                                            <option value="admin">Администратор</option>
                                            <option value="manager">Менеджер</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="" class="col-lg-2 col-sm-2 control-label">Новый пароль</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-lg-2 col-sm-2 control-label">Повторить пароль</label>
                                    <div class="col-lg-10">
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                    </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" class="btn btn-primary">Добавить</button>
                                    </div>
                                </div>
                            </div>
                        </form> 
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
        @endif

    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject">Персональные данные</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/{{ config('admin.path') }}/profile/edit" class="form-horizontal ajax__submit" method="POST">

                    {{ csrf_field() }}

                    <div class="form-body">
                         
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">E-mail</label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control" id="disabledInput" value="{{ Auth::user()->email }}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Имя</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Новый пароль</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Повторить пароль</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-9">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div> 

@if(count($users) && Auth::user()->type == 'admin')
<div class="row">
    <div class="col-md-12">
        <h3 class="form-section">Пользователи</h3>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 50px;">Имя</th>
                    <th>Логин/E-mail</th> 
                    <th>Тип</th>
                    <th style="width: 50px;"><i class="fa fa-cogs" aria-hidden="true"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td style="white-space: nowrap;">{{ ucfirst($user['name']) }}</td>
                        <td>{{ $user['email'] }}</td> 
                        <td>{{ $user->type == 'admin' ? 'Администратор' : 'Менеджер' }}</td>
                        <td style="white-space: nowrap;">
                            <input type="checkbox" 
                           class="make-switch" data-size="mini" {{ !empty($user['active']) ? 'checked' : '' }} 
                           data-on-text="<i class='fa fa-check'></i>" 
                           data-off-text="<i class='fa fa-times'></i>" 
                           onchange="Ajax.buttonView(this, '{{ $table }}', '{{ $user->id }}', 'active')">  
                           <a style="margin-left: 5px;" href="/{{ $method }}/{{ $user['id'] }}/edit-user/" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteModal_{{ $table }}_{{ $user['id'] }}">Delete</a>
                            <!-- Modal -->
                                @include('admin.utils.delete', ['id' => $user['id'], 'table' => $table])
                            <!-- Modal --> 
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@stop