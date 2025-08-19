<?php

namespace App\Models;

/**
 * Class Article (Optimized for Performance)
 * @package App\Models
 */
class Article extends ChocolateyModel
{

    /**
     * Disable Timestamps for performance
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chocolatey_articles';

    /**
     * Primary Key of the Table
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'content', 'author_id', 'category_id', 
        'image', 'thumbnail', 'timestamp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'integer',
        'author_id' => 'integer',
        'category_id' => 'integer',
    ];

    /**
     * Relationships - Author
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Relationships - Category
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * Scope for recent articles
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('id', 'DESC')->limit($limit);
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    /**
     * Store a new CMS Article
     *
     * @param string $title
     * @param string $description
     * @param string $content
     * @param string $author
     * @param string $categories
     * @param string $imageUrl
     * @param string $thumbnailUrl
     * @return Article
     */
    public function store(string $title, string $description, string $content, string $author, string $categories, string $imageUrl, string $thumbnailUrl): Article
    {
        $this->attributes['title'] = $title;
        $this->attributes['description'] = $description;
        $this->attributes['content'] = $content;
        $this->attributes['author'] = $author;
        $this->attributes['categories'] = $categories;
        $this->attributes['imageUrl'] = $imageUrl;
        $this->attributes['thumbnailUrl'] = $thumbnailUrl;

        return $this;
    }

    /**
     * Get formatted timestamp for display
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        return date('d/m/Y H:i', $this->timestamp);
    }

    /**
     * Get short content for previews (performance optimized)
     *
     * @param int $length
     * @return string
     */
    public function getExcerpt(int $length = 150): string
    {
        return str_limit(strip_tags($this->content), $length);
    }

    /**
     * Static method to get cached popular articles
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPopular(int $limit = 5)
    {
        return \Cache::remember("popular_articles_{$limit}", 3600, function () use ($limit) {
            return static::select(['id', 'title', 'image', 'timestamp'])
                         ->orderBy('id', 'DESC')
                         ->limit($limit)
                         ->get();
        });
    }

    /**
     * Static method to get cached articles by category
     *
     * @param int $categoryId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByCategoryId(int $categoryId, int $limit = 10)
    {
        return \Cache::remember("articles_category_{$categoryId}_{$limit}", 600, function () use ($categoryId, $limit) {
            return static::select(['id', 'title', 'content', 'image', 'timestamp', 'author_id'])
                         ->with(['author' => function ($query) {
                             $query->select(['id', 'username']);
                         }])
                         ->where('category_id', $categoryId)
                         ->orderBy('id', 'DESC')
                         ->limit($limit)
                         ->get();
        });
    }
}
