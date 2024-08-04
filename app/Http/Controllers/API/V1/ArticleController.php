<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\{
    ArticleCollection,
    ArticleResource
};
use App\Http\Requests\V1\{
    StoreArticleRequest,
    UpdateArticleRequest
};
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(): ArticleCollection
    {
        return new ArticleCollection(Article::paginate());
    }

    /**
     * Displays the specified resource
     *
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    /**
     * Stores a resource
     *
     * @param \Illuminate\Http\Request $request
     * @return ArticleResource
     */
    public function store(StoreArticleRequest $request): ArticleResource
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        return new ArticleResource(Article::create($data));
    }

    /**
     * @param Article $article
     * @param UpdateArticleRequest $request
     * @return void
     */
    public function update(UpdateArticleRequest $request) {
        $article = Article::find($request->route('id'));
        $article->update($request->all());
    }

    /**
     * @param Article $article
     * @return void
     */
    public function destroy(Request $request): void
    {
        $article = Article::find($request->route('id'));
        $article->delete();
    }
}
