@extends('layouts.public')

@section('content')
    <section class="change_pass">
        <div class="container">
            <form method="POST" class="ajax__submit" action="{{ route('save_new_password', ['lang' => lang()]) }}">
                {{ csrf_field() }}
                <div class="form__inner">
                    <label for="">
                        <span class="placeholder">{{ \Constant::get('NEW_PASS') }} *</span>
                        <input type="password" autocomplete="off" name="password" placeholder="***********">
                    </label>

                    <label for="">
                        <span class="placeholder">{{ \Constant::get('CONF_PASS') }} *</span>
                        <input  type="password" autocomplete="off" name="repeat_password">
                    </label>
                </div>
                <button type="submit">{{ \Constant::get('SAVE') }}</button>
            </form>
        </div>
    </section>
@stop

