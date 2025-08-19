<?php

namespace App\Models;

/**
 * Class Photo (Optimized for Performance)
 * @package App\Models
 */
class Photo extends ChocolateyModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'camera_web';

    /**
     * Primary Key of the Table
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Disable timestamps for performance
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'url', 'timestamp', 'room_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'integer',
        'user_id' => 'integer',
        'room_id' => 'integer',
    ];

    /**
     * Relationships - Creator/User
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationships - Likes
     */
    public function likes()
    {
        return $this->hasMany(PhotoLike::class, 'photo_id');
    }

    /**
     * Relationships - Reports  
     */
    public function reports()
    {
        return $this->hasMany(PhotoReport::class, 'photo_id');
    }

    /**
     * Store Function
     *
     * A photo can't be inserted by the CMS.
     * Only by the Emulator
     */
    public function store()
    {
        throw new InvalidMutatorException("You cannot store a Photo by Chocolatey. Photos need be created from the Server.");
    }

    /**
     * Get the Unique Id of the Photo
     *
     * @return string
     */
    public function getIdAttribute(): string
    {
        return "{$this->attributes['id']}";
    }

    /**
     * Get the URL of the Photo
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return str_replace('http:', '', str_replace('https:', '', $this->attributes['url']));
    }

    /**
     * Get the Version Attribute
     *
     * @return int
     */
    public function getVersionAttribute(): int
    {
        return 1;
    }

    /**
     * Get All Tags
     * Transforming it on an Array
     *
     * @return array(string)
     */
    public function getTagsAttribute(): array
    {
        return [];
    }

    /**
     * Get Formatted Time
     * Convert Date to UNIX Timestamp
     *
     * @return int
     */
    public function getTimeAttribute(): int
    {
        return strtotime($this->attributes['timestamp']) * 1000;
    }

    /**
     * Scope for photos with likes count (optimized)
     */
    public function scopeWithLikesCount($query)
    {
        return $query->withCount('likes');
    }

    /**
     * Scope for recent photos
     */
    public function scopeRecent($query, $limit = 50)
    {
        return $query->orderBy('timestamp', 'DESC')->limit($limit);
    }

    /**
     * Scope for not reported photos
     */
    public function scopeNotReported($query)
    {
        return $query->whereDoesntHave('reports', function ($q) {
            $q->where('status', 1); // approved reports
        });
    }

    /**
     * Get optimized photo data for API (cached)
     *
     * @return array
     */
    public function getApiDataAttribute(): array
    {
        return [
            'id' => (string) $this->id,
            'creator_id' => $this->user_id,
            'previewUrl' => $this->getCleanUrl(),
            'time' => $this->timestamp * 1000,
            'type' => 'PHOTO',
            'version' => 1,
            'tags' => [],
            'room_id' => $this->room_id ?? 1,
        ];
    }

    /**
     * Get clean URL (remove protocol for flexibility)
     *
     * @return string
     */
    public function getCleanUrl(): string
    {
        return str_replace(['http:', 'https:'], '', $this->url);
    }

    /**
     * Static method to get public photos with optimized queries
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublicPhotos(int $limit = 100)
    {
        return \Cache::remember("public_photos_{$limit}", 300, function () use ($limit) {
            return static::select(['id', 'user_id', 'url', 'timestamp', 'room_id'])
                         ->with(['creator' => function ($query) {
                             $query->select(['id', 'username']);
                         }])
                         ->notReported()
                         ->recent($limit)
                         ->get();
        });
    }

    /**
     * Get cached likes for this photo
     *
     * @return array
     */
    public function getCachedLikes(): array
    {
        return \Cache::remember("photo_likes_{$this->id}", 600, function () {
            return $this->likes()
                       ->select(['username'])
                       ->pluck('username')
                       ->toArray();
        });
    }

    /**
     * Clear cache when photo is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($photo) {
            \Cache::forget('public_photos_100');
            \Cache::forget("photo_likes_{$photo->id}");
        });

        static::deleted(function ($photo) {
            \Cache::forget('public_photos_100');
            \Cache::forget("photo_likes_{$photo->id}");
        });
    }
}
