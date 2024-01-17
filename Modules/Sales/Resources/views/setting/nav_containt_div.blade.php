@can('sales manage')
<div id="sales-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{__('Quote Print Settings')}}</h5>
        <small class="text-muted">{{__('')}}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{route('quote.template.setting')}}" enctype="multipart/form-data">
                @csrf
                <div class="row ms-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('quote_prefix',__('Prefix'),array('class'=>'form-label')) }}
                            {{Form::text('quote_prefix',!empty(company_setting('quote_prefix')) ? company_setting('quote_prefix') :'#QUO',array('class'=>'form-control', 'placeholder' => 'Enter Quote Prefix'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('quote_footer_title',__('Footer Title'),array('class'=>'form-label')) }}
                            {{Form::text('quote_footer_title',!empty(company_setting('quote_footer_title')) ? company_setting('quote_footer_title') :'',array('class'=>'form-control', 'placeholder' => 'Enter Footer Title'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('quote_footer_notes',__('Footer Notes'),array('class'=>'form-label')) }}
                            {{Form::textarea('quote_footer_notes',!empty(company_setting('quote_footer_notes')) ? company_setting('quote_footer_notes') : '',array('class'=>'form-control','rows'=>'1' ,'placeholder' => 'Enter Quote Footer Notes'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-2">
                            {{Form::label('quote_shipping_display',__('Shipping Display?'),array('class'=>'form-label')) }}
                            <div class=" form-switch form-switch-left">
                                <input type="checkbox" class="form-check-input" name="quote_shipping_display" id="quote_shipping_display" {{ (company_setting('quote_shipping_display')=='on')?'checked':''}} >
                                <label class="form-check-label" for="quote_shipping_display"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            <div class="form-group">
                                {{Form::label('quote_template',__('Template'),array('class'=>'form-label')) }}
                                {{ Form::select('quote_template',Modules\Sales\Entities\SalesUtility::templateData()['templates'],!empty(company_setting('quote_template')) ? company_setting('quote_template') : null, array('class' => 'form-control','required'=>'required')) }}
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('Color Input')}}</label>
                                <div class="row gutters-xs">
                                    @foreach( Modules\Sales\Entities\SalesUtility::templateData()['colors'] as $key => $color)
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="quote_color" type="radio" value="{{$color}}" class="colorinput-input" {{(!empty(company_setting('quote_color')) && company_setting('quote_color') == $color) ? 'checked' : ''}}>
                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Logo')}}</label>
                                <div class="choose-files mt-3">
                                    <label for="quote_logo">
                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <img id="blah7" class="mt-3" src=""  width="70%"  />
                                        <input type="file" class="form-control file" name="quote_logo" id="quote_logo" data-filename="quote_logo">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group text-end">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if(!empty( company_setting('quote_template')) && !empty(company_setting('quote_color')))
                        <iframe id="quote_frame" class="w-100 h-100" frameborder="0" src="{{route('quote.preview',[company_setting('quote_template'), company_setting('quote_color')])}}"></iframe>
                        @else
                        <iframe id="quote_frame" class="w-100 h-100" frameborder="0" src="{{route('quote.preview',['template1','fffff'])}}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<div id="salesorder-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{__('Sales Order Print Settings')}}</h5>
        <small class="text-muted">{{__('')}}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{route('salesorder.template.setting')}}" enctype="multipart/form-data">
                @csrf
                <div class="row ms-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('salesorder_prefix',__('Prefix'),array('class'=>'form-label')) }}
                            {{Form::text('salesorder_prefix',!empty(company_setting('salesorder_prefix')) ? company_setting('salesorder_prefix') :'#SLO',array('class'=>'form-control', 'placeholder' => 'Enter Quote Prefix'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('salesorder_footer_title',__('Footer Title'),array('class'=>'form-label')) }}
                            {{Form::text('salesorder_footer_title',!empty(company_setting('salesorder_footer_title')) ? company_setting('salesorder_footer_title') :'',array('class'=>'form-control', 'placeholder' => 'Enter Footer Title'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('salesorder_footer_notes',__('Footer Notes'),array('class'=>'form-label')) }}
                            {{Form::textarea('salesorder_footer_notes',!empty(company_setting('salesorder_footer_notes')) ? company_setting('salesorder_footer_notes') : '',array('class'=>'form-control','rows'=>'1' ,'placeholder' => 'Enter Quote Footer Notes'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-2">
                            {{Form::label('salesorder_shipping_display',__('Shipping Display?'),array('class'=>'form-label')) }}
                            <div class=" form-switch form-switch-left">
                                <input type="checkbox" class="form-check-input" name="salesorder_shipping_display" id="salesorder_shipping_display" {{ (company_setting('salesorder_shipping_display')=='on')?'checked':''}} >
                                <label class="form-check-label" for="salesorder_shipping_display"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            <div class="form-group">
                                {{Form::label('salesorder_template',__('Template'),array('class'=>'form-label')) }}
                                {{ Form::select('salesorder_template',Modules\Sales\Entities\SalesUtility::templateData()['templates'],!empty(company_setting('salesorder_template')) ? company_setting('salesorder_template') : null, array('class' => 'form-control','required'=>'required')) }}
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('Color Input')}}</label>
                                <div class="row gutters-xs">
                                    @foreach( Modules\Sales\Entities\SalesUtility::templateData()['colors'] as $key => $color)
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="salesorder_color" type="radio" value="{{$color}}" class="colorinput-input" {{(!empty(company_setting('salesorder_color')) && company_setting('salesorder_color') == $color) ? 'checked' : ''}}>
                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Logo')}}</label>

                                <div class="choose-files mt-3">
                                    <label for="salesorder_logo">
                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <img id="blah8" class="mt-3" src=""  width="70%"  />
                                        <input type="file" class="form-control file" name="salesorder_logo" id="salesorder_logo" data-filename="salesorder_logo_update">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-2 text-end">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if(!empty( company_setting('salesorder_template')) && !empty(company_setting('salesorder_color')))
                        <iframe id="salesorder_frame" class="w-100 h-100" frameborder="0" src="{{route('salesorder.preview',[company_setting('salesorder_template'), company_setting('salesorder_color')])}}"></iframe>
                        @else
                        <iframe id="salesorder_frame" class="w-100 h-100" frameborder="0" src="{{route('salesorder.preview',['template1','fffff'])}}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="salesinvoice-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{__('Sales Invoice Print Settings')}}</h5>
        <small class="text-muted">{{__('')}}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{route('salesinvoice.template.setting')}}" enctype="multipart/form-data">
                <div class="row ms-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('salesinvoice_prefix',__('Sales Invoice Prefix'),array('class'=>'form-label')) }}
                            {{Form::text('salesinvoice_prefix',!empty(company_setting('salesinvoice_prefix')) ? company_setting('salesinvoice_prefix') :'#INV',array('class'=>'form-control', 'placeholder' => 'Enter Invoice Prefix'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('salesinvoice_footer_title',__('Footer Title'),array('class'=>'form-label')) }}
                            {{Form::text('salesinvoice_footer_title',!empty(company_setting('salesinvoice_footer_title')) ? company_setting('salesinvoice_footer_title') :'',array('class'=>'form-control', 'placeholder' => 'Enter Footer Title'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{Form::label('salesinvoice_footer_notes',__('Footer Notes'),array('class'=>'form-label')) }}
                            {{Form::textarea('salesinvoice_footer_notes',!empty(company_setting('salesinvoice_footer_notes')) ? company_setting('salesinvoice_footer_notes') : '',array('class'=>'form-control','rows'=>'1' ,'placeholder' => 'Enter Quote Footer Notes'))}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-2">
                            {{Form::label('salesinvoice_shipping_display',__('Shipping Display?'),array('class'=>'form-label')) }}
                            <div class=" form-switch form-switch-left">
                                <input type="checkbox" class="form-check-input" name="salesinvoice_shipping_display" id="salesinvoice_shipping_display" {{ (company_setting('salesinvoice_shipping_display')=='on')?'checked':''}} >
                                <label class="form-check-label" for="salesinvoice_shipping_display"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            @csrf
                            <div class="form-group">
                                {{Form::label('salesinvoice_template',__('Template'),array('class'=>'form-label')) }}
                                {{ Form::select('salesinvoice_template',Modules\Sales\Entities\SalesUtility::templateData()['templates'],!empty(company_setting('salesinvoice_template')) ? company_setting('salesinvoice_template') : null, array('class' => 'form-control','required'=>'required')) }}
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{__('Color Input')}}</label>
                                <div class="row gutters-xs">
                                    @foreach( Modules\Sales\Entities\SalesUtility::templateData()['colors'] as $key => $color)
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="salesinvoice_color" type="radio" value="{{$color}}" class="colorinput-input" {{(!empty(company_setting('salesinvoice_color')) && company_setting('salesinvoice_color') == $color) ? 'checked' : ''}}>
                                            <span class="colorinput-color" style="background: #{{$color}}"></span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{__('Logo')}}</label>
                                <div class="choose-files mt-3">
                                    <label for="salesinvoice_logo">
                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <img id="blah9" class="mt-3" src=""  width="70%"  />
                                        <input type="file" class="form-control file" name="salesinvoice_logo" id="salesinvoice_logo" data-filename="sales_logo_update" >
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-2 text-end">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if(!empty( company_setting('salesinvoice_template')) && !empty(company_setting('salesinvoice_color')))
                        <iframe id="salesinvoice_frame" class="w-100 h-100" frameborder="0" src="{{route('salesinvoice.preview',[company_setting('salesinvoice_template'), company_setting('salesinvoice_color')])}}"></iframe>
                        @else
                        <iframe id="salesinvoice_frame" class="w-100 h-100" frameborder="0" src="{{route('salesinvoice.preview',['template1','fffff'])}}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endcan

<script>
    $(document).on("change", "select[name='quote_template'], input[name='quote_color']", function ()
    {
           var template = $("select[name='quote_template']").val();
           var color = $("input[name='quote_color']:checked").val();
           $('#quote_frame').attr('src', '{{url('/quote/preview')}}/' + template + '/' + color);
    });

       $(document).on("change", "select[name='salesorder_template'], input[name='salesorder_color']", function() {
           var template = $("select[name='salesorder_template']").val();
           var color = $("input[name='salesorder_color']:checked").val();
           $('#salesorder_frame').attr('src', '{{ url('/salesorder/preview') }}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='salesinvoice_template'], input[name='salesinvoice_color']", function() {
           var template = $("select[name='salesinvoice_template']").val();
           var color = $("input[name='salesinvoice_color']:checked").val();
           $('#salesinvoice_frame').attr('src', '{{ url('/salesinvoice/preview') }}/' + template + '/' + color);
        });
</script>
