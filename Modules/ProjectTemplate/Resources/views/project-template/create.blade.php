
@php
    $button = ($type == 'template') ? 'Save as Template' : 'Convert to Project';
    $title = ($type == 'template') ? 'Template' : 'Project';
    $placeholder = ($type == 'template') ? 'Project Template' : 'Project';
@endphp
{{ Form::open(array('route' => 'project-template.store','enctype'=>'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __($title.' Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required','placeholder'=> __('Enter '.$placeholder.' Name'))) }}
        </div>
        <input type="hidden" name="project_id" value="{{$projectId}}">
        <input type="hidden" name="type" value="{{$type}}">
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__($button)}}" class="btn  btn-primary" id="submit">
</div>
{{ Form::close() }}
