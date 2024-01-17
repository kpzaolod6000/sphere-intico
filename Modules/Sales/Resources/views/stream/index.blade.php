@extends('layouts.main')
@section('page-title')
    {{__('Manage Stream')}}
@endsection
@section('title')
        {{__('Stream')}}
@endsection
@section('page-breadcrumb')
    {{__('Stream')}}
@endsection
@section('page-action')
@endsection
@section('filter')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>{{__('Latest comments')}}</h5>
            </div>
            <div class="card-body">
            @foreach($streams as $stream)
                @php
                    $remark = json_decode($stream->remark);
                @endphp
                <div class="row">

                    <div class="col-xl-12">
                        <ul class="list-group">
                            <li class=" list-group-item border-0 d-flex align-items-start">
                                <div class="avatar col-1">
                                    <a href="{{ !empty($stream->file_upload) ? get_file($stream->file_upload) : get_file('uploads/users-avatar/avatar.png')}}" target="_blank">

                                        <img src="{{  !empty($stream->file_upload) ? get_file($stream->file_upload) :get_file('uploads/users-avatar/avatar.png') }}"
                                         class="user-image-hr-prj ui-w-30 rounded-circle" width="50px" height="50px">
                                     </a>
                                </div>
                                <div class="col-11 d-block d-sm-flex align-items-center right-side">
                                    <div class="col-10 d-flex align-items-start flex-column justify-content-center">
                                        <div class="h6">{{$remark->user_name}}
                                        </div>
                                        <span class="text-sm lh-140 mb-0">
                                            posted to <a href="#">{{$remark->title}}</a> , {{$stream->log_type}}  <a href="#">{{$remark->stream_comment}}</a>
                                        </span>
                                    </div>
                                    <div class="col-2 d-flex align-items-center ">
                                        <small class="text-end">{{$stream->created_at}}</small>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['stream.destroy', $stream->id]]) !!}
                                    <a href="#!" class="action-btn bg-danger mx-3 btn btn-sm  align-items-center text-white show_confirm" data-bs-toggle="tooltip" title='Delete'>
                                        <i class="ti ti-trash"></i>
                                    </a>
                                    {!! Form::close() !!}
                                    </div>
                                </div>

                            </li>

                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
