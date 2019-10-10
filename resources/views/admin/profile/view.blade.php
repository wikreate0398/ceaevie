@extends('layouts.admin')

@section('content') 
	<div class="row">
    <div class="col-md-12">
	
		<button class="btn btn-primary btn-sm" onclick="$('.hide__container').slideToggle();" style="margin-bottom: 20px;">
			<i class="fa fa-user" aria-hidden="true"></i> Add user
		</button>

        <div class="portlet light bordered hide__container" style="display: none;">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject">Add user</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/{{ config('admin.path') }}/profile/addNewUser" method="POST" class="form-horizontal ajax__submit">

                	{{ csrf_field() }}

                    <div class="form-body">
                    	<div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>

	                    <div class="form-group">
	                        <label for="" class="col-lg-2 col-sm-2 control-label">Login/E-mail</label>
	                        <div class="col-lg-10">
	                            <input type="text" class="form-control" name="email">
	                        </div>
	                    </div> 
 
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">New password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Repeat password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                            </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </form> 
                <!-- END FORM-->
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject">Personal Information</span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action="/{{ config('admin.path') }}/profile/edit" class="form-horizontal ajax__submit" method="POST">

                	{{ csrf_field() }}

                    <div class="form-body">
                         
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Login/E-mail</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="disabledInput" value="{{ Auth::user()->email }}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">New password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-lg-2 col-sm-2 control-label">Repeat password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-9">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div> 

@if(count($users))
<div class="row">
	<div class="col-md-12">
		<h3 class="form-section">Users</h3>
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th style="width: 50px;">Name</th>
					<th>Login/E-mail</th>
                    <th style="width: 5%; white-space: nowrap;">Log report</th>
					<th style="width: 50px;"><i class="fa fa-cogs" aria-hidden="true"></i></th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					<tr>
						<td style="white-space: nowrap;">{{ ucfirst($user['name']) }}</td>
						<td>{{ $user['email'] }}</td>
                        <td style="text-align: center">
                            @if($user->admin_logs_report->count())
                                <a href="{{ setAdminUri('profile/logs-report/' . $user->id) }}">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>
                            @else
                            @endif
                        </td>
						<td style="white-space: nowrap;">
							<input type="checkbox" 
	          		       class="make-switch" data-size="mini" {{ !empty($user['active']) ? 'checked' : '' }} 
	          		       data-on-text="<i class='fa fa-check'></i>" 
	          		       data-off-text="<i class='fa fa-times'></i>" 
	          		       onchange="Ajax.buttonView(this, '{{ $table }}', '{{ $user->id }}', 'active')">  
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