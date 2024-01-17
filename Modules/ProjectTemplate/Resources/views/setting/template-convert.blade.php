@can('project create')
    @if($project->type == 'template')
        <div class="col-sm-auto">
                <a class="btn btn-xs btn-primary btn-icon-only width-auto" data-ajax-popup="true"
                data-size="md" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Convert to project') }}" data-title="{{ __('Convert to project') }}"
                data-url="{{ route('project-template.create',['project_id'=>$project->id,'type'=>'project']) }}">
                <i class="ti ti-replace"></i>
            </a>
        </div>
    @endif
@endcan
