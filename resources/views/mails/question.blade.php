@component('mail::message')
 
<p>Имя: {{ $name }}</p>
<p>Телефон: {{ $phone }}</p>
<p>Сообщение: {{ $message }}</p>
  
 © {{ date('Y') }} {{ config('app.name') }}. @lang('Все права защищены.')
@endcomponent

 