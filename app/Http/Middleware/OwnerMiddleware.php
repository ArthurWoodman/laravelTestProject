<?php

namespace App\Http\Middleware;

use Closure;
use \App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $article = $request->route()->parameter('article') ?? Article::where('id', '=', $request->route('id'))->firstOrFail();

        if (!$article instanceof Article) {
            throw new \Exception('Article not found');
        }

        if ($article->user_id != Auth::id()) {
            throw new AccessDeniedHttpException('Only the owner of this article can access to the article');
        }

        return $next($request);
    }
}
