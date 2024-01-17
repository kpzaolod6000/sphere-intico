<?php

namespace Modules\Internalknowledge\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Internalknowledge\Entities\Article;
use Modules\Internalknowledge\Entities\Book;
use Illuminate\Support\Facades\Auth;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (\Auth::user()->can('article manage')) {
            $articles = Article::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $books = Book::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
            return view('internalknowledge::article.index', compact('articles', 'books'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $books = Book::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
        return view('internalknowledge::article.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('article create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'book' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $article              = new Article();
            $article->book        = $request->book;
            $article->title       = $request->title;
            $article->description = $request->description;
            $article->type        = $request->type;
            $article->content     = $request->content;
            $article->post_id     = Auth::user()->id;
            $article->created_by  = creatorId();
            $article->workspace   = getActiveWorkSpace();
            $article->save();

            return redirect()->back()->with('success', __('Article successfully created.'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Article $article)
    {
        $books = Book::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();
        return view('internalknowledge::article.edit', compact('article', 'books'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('article edit')) {
            $article = Article::find($id);

            $validator = \Validator::make(
                $request->all(),
                [
                    'book' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $article->book        = $request->book;
            $article->title       = $request->title;
            $article->description = $request->description;
            $article->type        = $request->type;
            $article->content     = $request->content;
            $article->post_id     = Auth::user()->id;
            $article->created_by  = creatorId();
            $article->workspace   = getActiveWorkSpace();
            $article->save();

            return redirect()->back()->with('success', __('Article successfully updated.'));
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
            $article = Article::find($id);
            $article->delete();

            return redirect()->back()->with('success', __('Article successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function copyarticle($id)
    {
        $article = Article::find($id);
        $books = Book::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->get();

        return view('internalknowledge::article.copy', compact('article', 'books'));
    }

    public function copyarticlestore(Request $request)
    {
        if (\Auth::user()->can('article create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'book' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $article              = new Article();
            $article->book        = $request->book;
            $article->title       = $request->title;
            $article->description = $request->description;
            $article->type        = $request->type;
            $article->content     = $request->content;
            $article->post_id     = Auth::user()->id;
            $article->created_by  = creatorId();
            $article->workspace   = getActiveWorkSpace();
            $article->save();

            return redirect()->back()->with('success', __('Article successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function grid()
    {
        $articles = Article::get();
        return view('internalknowledge::article.grid', compact('articles'));
    }

    public function mindmap(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'book' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $book = $request->book;
        $title = $request->title;
        $description = $request->description;
        $type = $request->type;

        $article              = new Article();
        $article->book        = $book;
        $article->title       = $title;
        $article->description = $description;
        $article->type        = $type;
        $article->post_id     = Auth::user()->id;
        $article->created_by  = creatorId();
        $article->workspace   = getActiveWorkSpace();
        $article->save();

        return response()->json([
            'status' => 200,
            'article_id' => $article->id,
        ]);
    }

    public function updateMindmap(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'id' => 'required', 
                'book' => 'required',
                'title' => 'required',
                'description' => 'required',
                'type' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $article = Article::find($request->input('id'));
        $content = json_decode($article->content);


        if (!$article) {
            return response()->json([
                'status' => 404,
                'error' => 'Article not found',
            ]);
        }

        $article->book = $request->input('book');
        $article->title = $request->input('title');
        $article->description = $request->input('description');
        $article->type = $request->input('type');
        $article->post_id     = Auth::user()->id;
        $article->created_by  = creatorId();
        $article->workspace   = getActiveWorkSpace();
        $article->save();

        return response()->json([
            'status' => 200,
            'message' => 'Article updated successfully',
            'article_id' => $article->id,
            'content' => $content,
        ]);
    }

    public function showMindmap(Request $request, $id)
    {
        $article = Article::find($id);
        $content = json_decode($article->content);
        return view('internalknowledge::article.show', compact('article', 'content'));
    }


    public function mindmapIndex(Request $request, $articleId)
    {
        $request->session()->put('articleId', $articleId);
        return view('internalknowledge::article.mindmap', compact('articleId'));
    }

    public function mindmapSave(Request $request, $key, $articleId)
    {
        $data = $request->data;
        $url = $request->newurl;

        $record = Article::find($articleId);
        $record->content = json_encode([
            'data' => $data,
            'newurl' => $url,
        ]);

        $record->save();

        return response()->json([
            'status' => 200,
            'message' => 'Data saved successfully',
            'record_id' => $record->id,
        ]);
    }

    public function getMindmap($articleId)
    {
        $record = Article::find($articleId);

        $content = json_decode($record->content);

        return $content->data;
    }
}