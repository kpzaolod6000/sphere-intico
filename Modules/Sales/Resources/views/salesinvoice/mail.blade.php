@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{!! $mail_header !!}
@endcomponent
@endslot


<body>
    <p style=" line-height:32px"><b style="font-weight:700">{{__('Subject : ').' invoice ('.$invoice->invoice.').'}}</b></p>
    <p style="line-height:32px"><b style="font-weight:700">{{__('Hi').' '.$invoice->reciverName.','}}</b></p>
    <p style="margin: 10px 0;">{{__('Hope this email ﬁnds you well! Please pay your invoice - invoice number').' '.$invoice->invoice.'.'}}</p>
    <p style="margin: 10px 0; text-align: center;">{{__(' simply click on the button below ')}}: </p>
</body>

@component('mail::button', ['url' => $invoice->url])
{{__('Invoice')}}
@endcomponent

@component('mail::panel')
<p style="margin: 10px 0;"><i style=":normal">{{__('Feel free to reach out if you have any questions.')}}</i></p>
<p style="margin: 10px 0;"><i style=":normal">{{__('Thank you for your business!')}}</i></p>


{{__('Regards,')}}<br/>
{{env('APP_NAME')}}
@endcomponent

                                                                
{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {!! $mail_header !!}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
