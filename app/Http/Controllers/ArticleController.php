<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class ArticleController
 * @package App\Http\Controllers
 */
class ArticleController extends BaseController
{
    /**
     * Render a specific view of Article set
     *
     * @param string $countryId
     * @param string $articleCategory
     * @return Response
     */
    public function many(string $countryId, string $articleCategory): Response
    {
        $category = ArticleCategory::find(strstr(($articleCategory =
            str_replace('.html', '', $articleCategory)), '_', true));

        $categoryPage = strstr(strrev($articleCategory), '_', true);

        return $articleCategory == 'front' ? $this->front() :
            $this->category($countryId, $category, $categoryPage,
                $categoryPage == 1 ? 0 : (10 * ($categoryPage - 1)));
    }

    /**
     * Render the Front Page of the Articles Page (Optimized)
     *
     * @return Response
     */
    protected function front(): Response
    {
        // Cache front page articles for 5 minutes
        $articles = \Cache::remember('articles_front', 300, function () {
            return Article::select(['id', 'title', 'content', 'timestamp', 'author_id', 'image'])
                         ->with(['author' => function ($query) {
                             $query->select(['id', 'username']);
                         }])
                         ->orderBy('id', 'DESC')
                         ->limit(10)
                         ->get();
        });

        return response(view('habbo-web-news.articles-front', ['set' => $articles]))
                ->header('Cache-Control', 'public, max-age=300');
    }

    /**
     * Render a specific Category Articles Page (Optimized)
     *
     * @param string $countryId
     * @param ArticleCategory $category
     * @param int $categoryPage
     * @param int $start
     * @return Response
     */
    protected function category(string $countryId, ArticleCategory $category, int $categoryPage, int $start): Response
    {
        // Cache categories list for 1 hour
        $categories = \Cache::remember('article_categories', 3600, function () {
            return ArticleCategory::select(['id', 'name', 'description'])->get();
        });

        // Build optimized query - filter in database, not in PHP
        $query = Article::select(['id', 'title', 'content', 'timestamp', 'author_id', 'image', 'category_id'])
                        ->with(['author' => function ($q) {
                            $q->select(['id', 'username']);
                        }]);

        // Apply category filter at database level
        if ($category->name !== 'all') {
            $query->where('category_id', $category->id);
        }

        $articles = $query->orderBy('id', 'DESC')
                         ->offset($start)
                         ->limit(10)
                         ->get();

        if ($articles->count() == 0) {
            return response()->json(['error' => 'No articles found'], 404);
        }

        return response(view('habbo-web-news.articles-category', [
            'category' => $category,
            'page' => $categoryPage,
            'categories' => $categories,
            'articles' => $articles
        ]))->header('Cache-Control', 'public, max-age=180'); // 3 minutes cache
    }

    /**
     * Render a specific view of a specific Article (Optimized)
     *
     * @param string $countryId
     * @param string $articleName
     * @return Response
     */
    public function one(string $countryId, string $articleName): Response
    {
        $articleId = strstr($articleName, '_', true);
        
        // Load article with author in single query
        $article = Article::select(['id', 'title', 'content', 'timestamp', 'author_id', 'image', 'category_id'])
                         ->with(['author' => function ($q) {
                             $q->select(['id', 'username']);
                         }])
                         ->find($articleId);

        if (!$article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        // Cache latest articles for 10 minutes
        $latest = \Cache::remember('latest_articles_5', 600, function () {
            return Article::select(['id', 'title', 'timestamp', 'image'])
                         ->orderBy('id', 'DESC')
                         ->limit(5)
                         ->get();
        });

        // Get related articles from same category (optimized)
        $related = \Cache::remember("related_articles_{$article->category_id}", 600, function () use ($article) {
            return Article::select(['id', 'title', 'timestamp', 'image'])
                         ->where('category_id', $article->category_id)
                         ->where('id', '!=', $article->id)
                         ->orderBy('id', 'DESC')
                         ->limit(5)
                         ->get();
        });

        return response(view('habbo-web-news.articles-view', [
            'article' => $article,
            'latest' => $latest,
            'related' => $related
        ]))->header('Cache-Control', 'public, max-age=600'); // 10 minutes cache for articles
    }

    /**
     * Get All Habbo Articles as XML/RSS (Optimized)
     *
     * @return Response
     */
    public function getRss()
    {
        // Cache RSS feed for 30 minutes
        $articles = \Cache::remember('rss_articles', 1800, function () {
            return Article::select(['id', 'title', 'content', 'timestamp', 'author_id'])
                         ->with(['author' => function ($q) {
                             $q->select(['id', 'username']);
                         }])
                         ->orderBy('id', 'DESC')
                         ->limit(20)
                         ->get();
        });

        return response(view('habbo-rss', ['articles' => $articles]))
                ->header('Content-Type', 'text/xml; charset=utf-8')
                ->header('Cache-Control', 'public, max-age=1800'); // 30 minutes cache
    }
}
