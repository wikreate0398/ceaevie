@extends('layouts.public')

@section('content')
    <section class="change_pass">
        <div class="container">
            <form method="POST" class="ajax__submit" action="{{ route('save_personal_data', ['lang' => lang()]) }}">
                {{ csrf_field() }}
                <div class="form__inner">
                    <label for="">
                        <span class="placeholder">{{ \Constant::get('NAME') }} *</span>
                        <input type="text" placeholder="" name="name" autocomplete="off" value="{{ Auth::user()->name }}">
                    </label>

                    <label for="">
                        <span class="placeholder">E-mail *</span>
                        <input  type="email" name="email" autocomplete="off" value="{{ Auth::user()->email }}">
                    </label>
                </div>
                <button type="submit">{{ \Constant::get('SAVE') }}</button>
            </form>
        </div>
    </section>
@stop

