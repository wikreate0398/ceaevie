@extends('layouts.public')

@section('content') 
    <form class="form-signin">
        <div class="text-center mb-4">
            <img class="mb-4" src="/docs/4.3/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Оставить чаевые</h1>
            <p>После ввода суммы, система перенаправит вас на завершающий этап оплаты</p>
        </div>

        <div class="form-label-group">
            <input type="email" name="amount" id="inputEmail" class="form-control" placeholder="Введите сумму" required autofocus>
            <label for="inputEmail">Введите сумму</label>
        </div>
        <div class="form-label-group">
            <select name="payment_method" required class="form-control">
                <option value="">Выбрать способ оплаты</option>
                <option value="visa">Visa</option>
                <option value="google">Google Pay</option>
                <option value="apple">Apple pay</option>
            </select> 
        </div>
     
        <button class="btn btn-lg btn-primary btn-block" type="submit">Оплатить</button>
        <p class="mt-5 mb-3 text-muted text-center">&copy; 2019</p>
    </form>
@stop

