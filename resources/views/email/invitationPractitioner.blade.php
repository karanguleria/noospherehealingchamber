@component('mail::message')

Welcome **{{$practitioner_name}}**!,

{{$name}} has submitted a Nosphere Healing questionnaire!

It is now ready for you to review.

Here is the result <br>
Excess  :  {{$excess}} %<br>
Balance  :  {{$balance}} %<br>
Insufficiency  :  {{$insufficiency}} %<br>

Warm regards, 

Exponential Healthcare
@endcomponent
