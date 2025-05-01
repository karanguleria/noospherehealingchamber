@component('mail::message')

Hello **{{$practitioner_name}}**,

{{$name}} has submitted their Nosphere Healing! It is now ready for you to review.

<a href="https://quantumevaluation.exponentialhealthcare.com/results/{{$result_id}}">https://quantumevaluation.exponentialhealthcare.com/results/{{$result_id}}</a>

Thank you,

Exponential Healthcare
@endcomponent
