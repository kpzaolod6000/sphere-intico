
 <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Salesy Saas- Business Sales CRM">
    <meta name="author" content="Rajodiya Infotech">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 </head>
@php
  $invoice=\Modules\Sales\Entities\SalesInvoice::find($data['invoice_id']);
@endphp



  <script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
  <div id="payment-form-container"> </div>
  <script>



    var brick = new Brick({
      public_key: '{{company_setting('company_paymentwall_public_key',$user_id,$workspace) }}', // please update it to Brick live key before launch your project
      amount: {{$data['amount']}},
      currency: '{{company_setting('defult_currancy',$user_id,$workspace)}}',
      container: 'payment-form-container',
      action: '{{route("invoice.pay.with.paymentwall",[$data["invoice_id"],"amount" => $data["amount"]])}}',

      form: {
        merchant: 'Paymentwall',
        product: '{{$invoice->name}}',
        pay_button: 'Pay',
        show_zip: true, // show zip code
        show_cardholder: true // show card holder name
      },



    });
    brick.showPaymentForm(function(data) {
      if(data.flag == 1){
        window.location.href ='{{route("error.invoice.show",[1,'invoice_id'])}}'.replace('invoice_id',data.invoice);
      }else{
        window.location.href ='{{route("error.invoice.show",[2,'invoice_id'])}}'.replace('invoice_id',data.invoice);
      }
    }, function(errors) {
      if(errors.flag == 1){
        window.location.href ='{{route("error.invoice.show",[1,'invoice_id'])}}'.replace('invoice_id',errors.invoice);
      }else{
        window.location.href ='{{route("error.invoice.show",[2,'invoice_id'])}}'.replace('invoice_id',errors.invoice);
      }
    });




  </script>
  <!-- {{route("pay.invoice",\Illuminate\Support\Facades\Crypt::encrypt($data["invoice_id"]))}} -->
