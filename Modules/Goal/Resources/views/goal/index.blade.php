@extends('layouts.main')
@section('page-title')
    {{ __('Manage Goals') }}
@endsection
@section('page-breadcrumb')
    {{ __('Manage Goals') }}
@endsection

@section('page-action')
    <div>
        @can('goal import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Goal Import') }}"
                data-url="{{ route('goal.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endcan
        @can('goal create')
            <a data-url="{{ route('goal.create') }}" data-bs-toggle="tooltip" data-size="lg" title="{{ __('Create') }}"
                data-ajax-popup="true" data-title="{{ __('Create New Goal') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Type') }}</th>
                                    <th> {{ __('From') }}</th>
                                    <th> {{ __('To') }}</th>
                                    <th> {{ __('Amount') }}</th>
                                    <th> {{ __('Is Dashboard Display') }}</th>
                                    @if (Gate::check('goal edit') || Gate::check('goal delete'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($golas as $gola)
                                    <tr>
                                        <td class="font-style">{{ $gola->name }}</td>
                                        <td class="font-style">
                                            {{ __(\Modules\Goal\Entities\Goal::$goalType[$gola->type]) }} </td>
                                        <td class="font-style">{{ $gola->from }}</td>
                                        <td class="font-style">{{ $gola->to }}</td>
                                        <td class="font-style">{{ currency_format_with_sym($gola->amount) }}</td>
                                        <td class="font-style">{{ $gola->is_display == 1 ? __('Yes') : __('No') }}</td>
                                        <td class="Action">
                                            <span>
                                                @can('goal edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a class="mx-3 btn btn-sm align-items-center"
                                                            data-url="{{ route('goal.edit', $gola->id) }}"
                                                            data-ajax-popup="true" data-size="lg"
                                                            data-title="{{ __('Edit Goal') }}" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan


                                                @can('goal delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['goal.destroy', $gola->id],
                                                            'id' => 'delete-form-' . $gola->id,
                                                        ]) !!}
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
