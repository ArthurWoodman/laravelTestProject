<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function get()
    {
        return Article::query()->orderBy('created_at', 'desc')->get();
    }

    public function create(Request $request)
    {
        // max value is just for example
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'publication_date' => 'required|date',
        ]);

        Article::create([
            'title' => $request->title,
            'body' => $request->body,
            'publication_date' => $request->publication_date,
            'slug' => str_replace(' ', '-' ,$request->title),
            'user_id' => Auth::user()->id,
        ]);

        return redirect('articles');
    }

    public function delete(Request $request)
    {
        /** @var Article $article */
        $article = Article::where('id', '=', $request->route('id'))->first();

        $article->delete();

        return redirect('articles');
    }

    public function read($id)
    {
        return Article::where('id', '=', $id)->first();
    }

    public function update(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'publication_date' => 'required|date',
        ]);

        $article = Article::find($request->route('id'));
        if (auth()->user()->id !== $article->user_id) {
            return redirect('articles')->with('error', 'Unauthorized action.');
        }

        $article->update([
            'title' => $request->title,
            'body' => $request->body,
            'publication_date' => $request->publication_date,
        ]);

        return redirect('articles');
    }
}
