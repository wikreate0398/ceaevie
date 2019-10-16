@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      <i class="mdi mdi-currency-usd"></i>
    </span> Ваш баланс</h3>
    </div>
    <div class="row">
        <div class="col-md-4 grid-margin">
            <div
                class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <h4 class="font-weight-normal mb-3">
                        Текущий баланс для вывода
                    </h4>
                    <h2>0 P <span> / 3000 P</span></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 grid-margin">
            <button type="submit"
                    class="btn btn-gradient-info btn-rounded btn-block"
                    style="min-height: 55px">
                Заказать вывод средств
            </button>
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-added">
                <div class="card-body">
                    <span class="name">IVAN SMIRNOV</span>
                    <span class="expiration-date">08/19</span>
                    <span
                        class="card-nr">4276   ....   ....   ..96</span>
                    <div class="card-type">
                        <img
                            src="{{ asset('profile_theme') }}/assets/images/dashboard/visa.png"
                            alt="card-type">
                        <img
                            src="{{ asset('profile_theme') }}/assets/images/socials/sberbank.png"
                            alt="bank">
                    </div>
                    <img src="{{ asset('profile_theme') }}/assets/images/trash.png"
                         alt="delete">
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card add-card">
                <div class="card-body align-center">
                    <div class="ellips" data-toggle="modal"
                         data-target="#myModal">+
                    </div>
                    <p>Привязать <br> банковскую карту</p>
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    
    <form action="">
        <div class="row">
            <div class="col-md-12">
                <h3 class="title-section">История вывода
                    средств</h3>
                <h4 class="title">Отобразить платежи за период</h4>
            </div>
            
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="mm/dd/yy">
                    <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                         alt="calendar">
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="mm/dd/yy">
                    <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                         alt="calendar">
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <button type="submit"
                        class="btn btn-gradient-info btn-rounded btn-block"
                        style="min-height: 55px">Показать
                </button>
            </div>
        </div>
    </form>
    
    <form action="">
        <div class="row">
            <div class="col-md-8 grid-margin">
                <div class="inline">
                    <label for="itemsPerPageSelect">Показывать
                        по</label>
                    <select
                        class="form-control form-control-lg per-page"
                        id="itemsPerPageSelect">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    
                    <button type="button" class="sort-transaction">
                        За весь
                        период
                    </button>
                    <button type="button" class="sort-transaction">
                        За
                        неделю
                    </button>
                    <button type="button" class="sort-transaction">
                        За
                        месяц
                    </button>
                </div>
            
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control"
                           placeholder="Поиск по номеру транзакции"/>
                </div>
            </div>
        </div>
    </form>
    
    <div class="row">
        <div class="col-md-12 grid-margin table-history">
            <table class="history">
                <thead>
                <tr>
                    <td>Номер транзакции</td>
                    <td>Дата зачисления <i
                        class="mdi mdi-chevron-down"></i></td>
                    <td>Сумма <i class="mdi mdi-chevron-down"></i>
                    </td>
                    <td>Номер карты <i
                        class="mdi mdi-chevron-down"></i></td>
                    <td>Статус <i class="mdi mdi-chevron-down"></i>
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>8451233</td>
                    <td>22.12.2019</td>
                    <td>100 Р</td>
                    <td class="code">4276 .... .... ..96</td>
                    <td>Не введен код авторизации</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6 align-center">
            <span>Показано 10 из 16</span>
        </div>
        <div class="col-md-6">
            <ul class="pages">
                <li>
                    <a href="#"><i class="mdi mdi-chevron-left"></i></a>
                </li>
                <li class="active"><a href="#1">1</a></li>
                <li><a href="#2">2</a></li>
                <li>
                    <a href="#"><i
                        class="mdi mdi-chevron-right"></i></a>
                </li>
            </ul>
        </div>
    </div>

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
                    <h2>Привязать карту</h2>
                    <form class="forms-sample row">
                        <div class="form-group col-md-12">
                            <label for="ownerCardInput1">Укажите держателя
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="ownerCardInput1"
                                   placeholder="Имя фамилия на латинице">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="cardNrInput1">Укажите номер
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="cardNrInput1"
                                   placeholder="4276   ....   ....   ..96">
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="expirationDateOfCard">Срок действия
                                карты*</label>
                            <input type="text" class="form-control"
                                   id="expirationDateOfCard"
                                   placeholder="11/19">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cvvInput">CSV*</label>
                            <input type="text" class="form-control"
                                   id="cvvInput"
                                   placeholder="123">
                        </div>
                        
                        <div style="text-align: center" class="col-md-12">
                            <button type="submit"
                                    class="btn btn-gradient-info mr-2">Привязать
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

