<?php

namespace Modules\Sales\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sales\Entities\Stream;

class StreamController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->can('stream manage'))
        {
            $streams = Stream::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->orderBy('id', 'desc')->get();

            return view('sales::stream.index', compact('streams'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('sales::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('sales::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('sales::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Stream $stream)
    {
        if(\Auth::user()->can('stream delete'))
        {
            if(!empty($stream->file_upload))
            {
                delete_file($stream->file_upload);
            }
            $stream->delete();

            return redirect()->back()->with(
                'success', 'Stream Deleted!'
            );
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function streamstore(Request $request, $title, $name, $id)
    {
        if(\Auth::user()->can('stream create'))
        {
            $user      = \Auth::user()->id;

            $validator = \Validator::make(
                $request->all(), [
                                'attachment' => 'image',
                            ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if(!empty($request->attachment))
            {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('attachment')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    $uplaod = upload_file($request,'attachment',$fileNameToStore,'Stream');
                    if($uplaod['flag'] == 1)
                    {
                        $url = $uplaod['url'];
                    }
                    else
                    {
                        return redirect()->back()->with('error',$uplaod['msg']);
                    }
            }

            Stream::create(
                [
                    'user_id' => $user,
                    'log_type' => $request->log_type,
                    'file_upload' => !empty($request->attachment) ? $url : '',
                    'remark' => json_encode(
                        [
                            'owner_name' => \Auth::user()->username,
                            'stream_comment' => $request->stream_comment,
                            'title' => $title,
                            'data_id' => $id,
                            'user_name' => $name,
                        ]
                    ),
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ]
            );

            return redirect()->back()->with('success', __('Stream Successfully Created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
