
{{ Form::model($invoice, array('route' => array('invoices.payments.store', $invoice->id), 'method' => 'POST')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                    {{ Form::number('amount', $invoice->getDue(), array('class' => 'form-control','required'=>'required','min'=>'0',"step"=>"0.01")) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Payment Date'),['class'=>'form-label']) }}
                    {{ Form::text('date', null, array('class' => 'form-control datepicker','required'=>'required')) }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('payment_id', __('Payment Method'),['class'=>'form-label']) }}
                    {{ Form::select('payment_id', $payment_methods,null, array('class' => 'form-control  select2','required'=>'required')) }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('notes', __('Notes'),['class'=>'form-label']) }}
                    {{ Form::textarea('notes', null, array('class' => 'form-control','rows'=>'2')) }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{Form::submit(__('Add'),array('class'=>'btn btn-primary'))}}
    </div>

{{ Form::close() }}

