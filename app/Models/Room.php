<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class Room (Performance Optimized)
 * @property int id
 * @package App\Models
 */
class Room extends Model
{

    /**
     * Disable Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Leader Board Rank
     *
     * @var int
     */
    public $leaderboardRank = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';

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
        'name', 'description', 'model', 'users_max', 'category',
        'paper_floor', 'paper_wall', 'paper_landscape', 'owner_id', 'owner_name',
        'tags', 'state', 'password', 'score'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'owner_id' => 'integer',
        'users_max' => 'integer',
        'category' => 'integer',
        'paper_floor' => 'integer',
        'paper_wall' => 'integer',
        'paper_landscape' => 'float',
        'is_public' => 'boolean',
        'score' => 'integer',
        'state' => 'integer',
        'users' => 'integer',
    ];

    /**
     * The Appender(s) of the Model
     *
     * @var array
     */
    protected $appends = [
        'uniqueId',
        'leaderboardRank',
        'thumbnailUrl',
        'imageUrl',
        'leaderboardValue',
        'doorMode',
        'maximumVisitors',
        'publicRoom',
        'ownerUniqueId',
        'ownerName',
        'showOwnerName',
        'categories',
        'rating'
    ];
    
    /**
     * Relationships - Room Owner
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id')
                    ->select(['id', 'username', 'rank', 'last_online']);
    }
    
    /**
     * Relationships - Room Category
     */
    public function roomCategory()
    {
        return $this->belongsTo(FlatCat::class, 'category')
                    ->select(['id', 'caption']);
    }
    
    /**
     * Relationships - Room Guild/Group
     */
    public function guild()
    {
        return $this->belongsTo(GroupMember::class, 'guild_id')
                    ->select(['id', 'name', 'description']);
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'model',
        'guild_id',
        'paper_floor',
        'paper_wall',
        'paper_landscape',
        'thickness_wall',
        'wall_height',
        'thickness_floor',
        'moodlight_data',
        'is_staff_picked',
        'allow_other_pets',
        'allow_other_pets_eat',
        'allow_walkthrough',
        'allow_hidewall',
        'chat_mode',
        'chat_weight',
        'chat_speed',
        'chat_hearing_distance',
        'chat_protection',
        'override_model',
        'who_can_mute',
        'who_can_kick',
        'who_can_ban',
        'poll_id',
        'roller_speed',
        'promoted',
        'trade_mode',
        'move_diagonally'
    ];

    /**
     * Stores a new Room
     *
     * @param string $roomName
     * @param string $description
     * @param string $model
     * @param int $maxUsers
     * @param int $roomCategory
     * @param int $floorPaper
     * @param int $wallPaper
     * @param float $landscapePaper
     * @param int $ownerId
     * @param string $ownerName
     * @return Room
     */
    public function store(string $roomName, string $description, string $model, int $maxUsers, int $roomCategory, int $floorPaper, int $wallPaper, float $landscapePaper = 0.00, int $ownerId, string $ownerName)
    {
        $this->attributes['name'] = $roomName;
        $this->attributes['description'] = $description;
        $this->attributes['model'] = $model;
        $this->attributes['users_max'] = $maxUsers;
        $this->attributes['category'] = $roomCategory;
        $this->attributes['paper_floor'] = $floorPaper;
        $this->attributes['paper_wall'] = $wallPaper;
        $this->attributes['paper_landscape'] = $landscapePaper;
        $this->attributes['thickness_wall'] = 0;
        $this->attributes['wall_height'] = -1;
        $this->attributes['thickness_floor'] = 0;
        $this->attributes['owner_id'] = $ownerId;
        $this->attributes['owner_name'] = $ownerName;

        return $this;
    }

    /**
     * Get Room Tags
     *
     * @return array
     */
    public function getTagsAttribute(): array
    {
        return array_filter(explode(';', $this->attributes['tags']), function ($element) {
            return !empty($element);
        });
    }

    /**
     * Get Image Url
     *
     * @TODO: Get Real Full Room Image
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return "//arcturus.wf/full_{$this->attributes['id']}.png";
    }

    /**
     * Get Thumbnail Url
     *
     * @return string
     */
    public function getThumbnailUrlAttribute(): string
    {
        $userName = Config::get('chocolatey.arcturus');

        return "//arcturus.wf/camera/{$userName}/thumbnail_{$this->attributes['id']}.png";
    }

    /**
     * Return if need show Owner Name
     *
     * @TODO: What this really does?
     *
     * @return bool
     */
    public function getShowOwnerNameAttribute(): bool
    {
        return true;
    }

    /**
     * Set a Leader Board Position
     *
     * @param int $roomPosition
     */
    public function setLeaderBoardRankAttribute(int $roomPosition = 1)
    {
        $this->leaderboardRank = $roomPosition;
    }

    /**
     * Get Leader Board Rank
     *
     * @return int
     */
    public function getLeaderBoardRankAttribute(): int
    {
        return $this->leaderboardRank;
    }

    /**
     * Get if the Room is Public
     *
     * @return bool
     */
    public function getPublicRoomAttribute(): bool
    {
        return $this->attributes['is_public'] == 1;
    }

    /**
     * Get Room Category (optimized)
     *
     * @return array
     */
    public function getCategoriesAttribute(): array
    {
        return \Cache::remember("room_category_{$this->category}", 3600, function () {
            $category = FlatCat::find($this->category);
            if (!$category) {
                return ['Unknown'];
            }
            return [str_replace('}', '', str_replace('${', '', $category->caption ?? 'Unknown'))];
        });
    }
    
    /**
     * Accessor methods for mapped attributes
     */
    public function getUniqueIdAttribute(): int
    {
        return $this->id;
    }
    
    public function getOwnerNameAttribute(): string
    {
        return $this->attributes['owner_name'] ?? '';
    }
    
    public function getOwnerUniqueIdAttribute(): int
    {
        return $this->attributes['owner_id'] ?? 0;
    }
    
    public function getDoorModeAttribute(): int
    {
        return $this->attributes['state'] ?? 0;
    }
    
    public function getLeaderboardValueAttribute(): int
    {
        return $this->attributes['score'] ?? 0;
    }
    
    public function getMaximumVisitorsAttribute(): int
    {
        return $this->attributes['users_max'] ?? 25;
    }
    
    public function getRatingAttribute(): int
    {
        return $this->attributes['score'] ?? 0;
    }
    
    /**
     * Scopes for optimized queries
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', 1);
    }
    
    public function scopePrivate($query)
    {
        return $query->where('is_public', 0);
    }
    
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category', $categoryId);
    }
    
    public function scopePopular($query, int $limit = 20)
    {
        return $query->orderBy('score', 'desc')
                    ->orderBy('users', 'desc')
                    ->limit($limit);
    }
    
    public function scopeByOwner($query, int $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }
    
    /**
     * Static method to get popular rooms with caching
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPopularRooms(int $limit = 20)
    {
        return \Cache::remember("popular_rooms_{$limit}", 300, function () use ($limit) {
            return static::select(['id', 'name', 'description', 'owner_name', 'users', 'users_max', 'score'])
                         ->with(['owner' => function ($query) {
                             $query->select(['id', 'username']);
                         }])
                         ->where('is_public', 0) // Only private rooms for popular list
                         ->orderBy('score', 'desc')
                         ->orderBy('users', 'desc')
                         ->limit($limit)
                         ->get();
        });
    }
    
    /**
     * Static method to get public rooms with caching
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublicRooms()
    {
        return \Cache::remember('public_rooms', 1800, function () {
            return static::select(['id', 'name', 'description', 'users', 'users_max'])
                         ->where('is_public', 1)
                         ->orderBy('name')
                         ->get();
        });
    }
    
    /**
     * Get room data optimized for API
     *
     * @return array
     */
    public function getApiDataAttribute(): array
    {
        return [
            'uniqueId' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'ownerName' => $this->owner_name,
            'ownerUniqueId' => $this->owner_id,
            'doorMode' => $this->state,
            'maximumVisitors' => $this->users_max,
            'userCount' => $this->users ?? 0,
            'rating' => $this->score ?? 0,
            'publicRoom' => $this->is_public == 1,
            'thumbnailUrl' => $this->thumbnailUrl,
            'imageUrl' => $this->imageUrl,
            'tags' => $this->tags,
        ];
    }
    
    /**
     * Clear cache when room is updated
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($room) {
            \Cache::forget('popular_rooms_20');
            \Cache::forget('public_rooms');
            \Cache::forget("room_category_{$room->category}");
        });
        
        static::deleted(function ($room) {
            \Cache::forget('popular_rooms_20');
            \Cache::forget('public_rooms');
            \Cache::forget("room_category_{$room->category}");
        });
    }
}
