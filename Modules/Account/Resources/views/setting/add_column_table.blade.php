<div class="form-group col-md-6">
    {{ Form::label('sale_chartaccount_id', __('Income Account'),['class'=>'form-label']) }}
    {{ Form::select('sale_chartaccount_id',$incomeChartAccounts,null, array('class' => 'form-control select','required'=>'required')) }}
</div>
<div class="form-group col-md-6">
    {{ Form::label('expense_chartaccount_id', __('Expense Account'),['class'=>'form-label']) }}
    {{ Form::select('expense_chartaccount_id',$expenseChartAccounts,null, array('class' => 'form-control select','required'=>'required')) }}
</div>
