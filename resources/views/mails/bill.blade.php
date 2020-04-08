@component('mail::message')
    <table style="width: 100%;">
        <tr>
            <td style="width: 200px; padding-bottom: 10px;">
                <img style="width: 100%;" src="{{ asset('img/pd-mail-logo.png') }}" alt="">
            </td>

            <td style="font-size: 18px; text-align: right; color: #666; padding-bottom: 10px;">
                Счет на оплату № {{ $order_number }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold; font-size: 24px;color: #666; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px solid #ededed;">
                Вам выставлен счет для оплаты заказа:
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 10px;">Получатель: {{ $waiter }}</td>
        </tr>
        <tr>
            <td colspan="2">Номер заказа: {{ $order_number }}</td>
        </tr>
        <tr>
            <td colspan="2">Сумма платежа: {{ $price }} RUB</td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom: 10px; border-bottom: 2px solid #ededed;">Описание: Чаевые официанту {{ $waiter }}</td>
        </tr>
        <tr>
            <td style="width: 50%; font-size: 13px; font-weight: 300; padding-top: 20px;">
                Вы можете произвести оплату при помощи банковской карты Visa\MasterCard, для этого нажмите кнопку "ОПЛАТИТЬ". Вы будете перенаправлены на страницу оплаты.
            </td>
            <td style="width: 50%; padding-top: 20px; text-align: center">
                @component('mail::button', ['url' => $link, 'color' => 'green'])
                   Оплатить
                @endcomponent
                <img src="{{ asset('img/cards.png') }}" style="width: 150px;">
            </td>
        </tr>
    </table>
@endcomponent

