@component('mail::message')

Dear **{{$name}}**,

Thank you for participating in Nosphere Healing, a web-based application designed to assess the balance of the human body based on responses to questions related to physical and vital-mental health. I am pleased to provide you with your personalized report based on your responses.

Here is the result <br>
Excess:  {{$excess}} %<br>
Balance:  {{$balance}} %<br>
Insufficiency:  {{$insufficiency}} %<br>

Excess Explanation: {{$excess_message}} <br>

Balance Explanation: {{$insufficiency_message}} <br>

Insufficiency Explanation: {{$balance_message}} <br>

Please find your results attached, along with a detailed explanation.

I will review your report and then reach out to discuss your results in more depth. Together, we can identify specific strengths and areas for improvement in terms of your physical and mental health. I will also provide recommended actions that you can take to improve your health.

You may also reach out to me with any questions via our normal communication method, inside your Exponential Healthcare client dashboard under “Secure Messaging.”

Please note that the Nosphere Healing is not intended to diagnose; it is used only as a means of gaining insight into the underlying cause of your complaint(s). I am looking forward to our discussion! 


Thank you again for your participation in this project. I hope that your personalized report will help you take steps towards achieving greater health and well-being.

Best regards,

{{$practitioner_name}}
@endcomponent
