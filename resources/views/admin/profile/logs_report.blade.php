@extends('layouts.admin')

@section('content')

    @if($user->admin_logs_report->count())
        <div class="row">
            <div class="col-md-12">

                <div class="list-group">


                    @foreach($user->admin_logs_report as $log)

                        <a href="javascript:;" class="list-group-item">
                            <h4 class="list-group-item-heading"><b>{{ $log->log_define->log_name }}</b> / <span class="badge badge-info">{{ $log->created_at->format('d.m.Y H:i') }}</span></h4>
                            <p class="list-group-item-text">
                                {{ d(json_decode($log->data, true)) }}
                            </p>
                        </a>

                    @endforeach

                </div>

                <a href="{{ setAdminUri('profile') }}" class="btn btn-sm btn-info">Back to users list</a>

            </div>
        </div>
    @endif
@stop