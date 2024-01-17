<?php

namespace Modules\Internalknowledge\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Internalknowledge\Entities\Book;
use Modules\Internalknowledge\Entities\Article;
use Illuminate\Support\Facades\Auth;
use Modules\Performance\Http\Controllers\CompetenciesController;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $books = Book::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
        $users = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
        return view('internalknowledge::book.index', compact('books', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $users = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
        // $users  = User::where('created_by',creatorId())->emp()->where('workspace_id',getActiveWorkSpace())->orWhere('id',Auth::user()->id)->get();
        return view('internalknowledge::book.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if (\Auth::user()->can('book create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'user_id' => 'required',
                    // 'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $book              = new Book();
            $book->title       = $request->title;
            $book->description = $request->description;
            $book->user_id     = implode(",", $request->user_id);
            $book->created_by  = creatorId();
            $book->workspace   = getActiveWorkSpace();
            $book->save();

            return redirect()->back()->with('success', __('Book successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $articles   = Article::where('book',$id)->get();
        $articleCounts = [];
        return view('internalknowledge::book.show', compact('articles'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Book $book)
    {
        $users = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get();
        $selectedUserIds = explode(',', $book->user_id);
        return view('internalknowledge::book.edit', compact('book', 'users', 'selectedUserIds'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('book edit')) {
            $book = Book::find($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'user_id' => 'required',
                    // 'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $book->title       = $request->title;
            $book->description = $request->description;
            $book->user_id     = implode(",", $request->user_id);
            $book->created_by  = creatorId();
            $book->save();

            return redirect()->back()->with('success', __('Book successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->can('book delete')) {
            $book = Book::find($id);
            $book->delete();

            return redirect()->back()->with('success', __('Book successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function grid()
    {
        $books = Book::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
        return view('internalknowledge::book.grid', compact('books'));
    }
}
