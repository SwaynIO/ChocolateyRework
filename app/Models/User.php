<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class User
 * @property string trusted
 * @property int uniqueId
 * @property string figureString
 * @property string name
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * Disable Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * User Traits
     *
     * @var array
     */
    public $traits = ["USER"];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Primary Key of the Table
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The Appender(s) of the Model
     *
     * @var array
     */
    protected $appends = [
        'habboClubMember',
        'buildersClubMember',
        'sessionLoginId',
        'loginLogId',
        'identityVerified',
        'identityType',
        'trusted',
        'country',
        'traits',
        'uniqueId',
        'name',
        'figureString',
        'lastWebAccess',
        'creationTime',
        'email',
        'identityId',
        'emailVerified',
        'accountId',
        'memberSince',
        'isBanned',
        'banDetails'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mail',
        'id',
        'username',
        'auth_ticket',
        'last_login',
        'ip_current',
        'ip_register',
        'mail_verified',
        'account_day_of_birth',
        'real_name',
        'look',
        'gender'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'username',
        'mail',
        'account_created',
        'password',
        'mail_verified',
        'real_name',
        'account_day_of_birth',
        'last_online',
        'last_login',
        'ip_register',
        'auth_ticket',
        'home_room',
        'points',
        'look',
        'ip_current',
        'online',
        'pixels',
        'credits',
        'gender',
        'points',
        'rank'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'traits' => 'string',
        'account_created' => 'integer',
        'last_login' => 'integer',
        'last_online' => 'integer',
        'mail_verified' => 'boolean',
        'online' => 'boolean',
        'rank' => 'integer',
        'credits' => 'integer',
        'pixels' => 'integer',
        'points' => 'integer',
    ];

    /**
     * Store an User on the Database
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $address
     * @return User
     */
    public function store(string $username, string $password, string $email, string $address = ''): User
    {
        $this->attributes['username'] = $username;
        $this->attributes['mail'] = $email;

        $this->attributes['motto'] = Config::get('chocolatey.motto');
        $this->attributes['look'] = Config::get('chocolatey.figure');
        $this->attributes['auth_ticket'] = '';

        $this->attributes['password'] = hash(Config::get('chocolatey.security.hash'), $password);
        $this->attributes['account_created'] = time();

        $this->attributes['ip_current'] = $address;

        $this->traits = ["NEW_USER", "USER"];

        return $this;
    }

    /**
     * Store an User Alias Set on Database
     */
    public function createData()
    {
        (new ChocolateyId)->store($this->attributes['id'], $this->attributes['mail'])->save();

        (new UserPreferences)->store($this->attributes['id'])->save();
    }

    /**
     * Relationships - Ban Details (optimized)
     */
    public function banDetails()
    {
        return $this->hasOne(Ban::class, 'user_id')
                    ->select(['id', 'user_id', 'reason', 'timestamp', 'expires', 'banned_by']);
    }
    
    /**
     * Relationships - User Security
     */
    public function security()
    {
        return $this->hasOne(UserSecurity::class, 'user_id')
                    ->select(['id', 'user_id', 'trusted_devices']);
    }
    
    /**
     * Relationships - User Preferences
     */
    public function preferences()
    {
        return $this->hasOne(UserPreferences::class, 'user_id');
    }
    
    /**
     * Relationships - User Profile
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
    
    /**
     * Relationships - User Friends
     */
    public function friends()
    {
        return $this->hasMany(UserFriend::class, 'user_one_id')
                    ->select(['id', 'user_one_id', 'user_two_id', 'confirmed']);
    }
    
    /**
     * Relationships - User Groups
     */
    public function groups()
    {
        return $this->belongsToMany(GroupMember::class, 'group_members', 'user_id', 'group_id')
                    ->select(['group_id', 'user_id', 'is_admin']);
    }
    
    /**
     * Relationships - User Badges
     */
    public function badges()
    {
        return $this->hasMany(UserBadge::class, 'user_id')
                    ->select(['id', 'user_id', 'badge_code', 'slot_id']);
    }
    
    /**
     * Relationships - Photos created by user
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'user_id')
                    ->select(['id', 'user_id', 'url', 'timestamp']);
    }
    
    /**
     * Relationships - Articles authored by user
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id')
                    ->select(['id', 'author_id', 'title', 'timestamp']);
    }

    /**
     * Scope for active (non-banned) users
     */
    public function scopeActive($query)
    {
        return $query->whereDoesntHave('banDetails');
    }

    /**
     * Scope for online users
     */
    public function scopeOnline($query)
    {
        return $query->where('online', 1);
    }

    /**
     * Scope for users by rank
     */
    public function scopeByRank($query, $rank)
    {
        return $query->where('rank', $rank);
    }

    /**
     * Scope for staff users (rank >= 6)
     */
    public function scopeStaff($query)
    {
        return $query->where('rank', '>=', 6);
    }

    /**
     * Scope for admin users (rank >= 7)
     */
    public function scopeAdmin($query)
    {
        return $query->where('rank', '>=', 7);
    }

    /**
     * Get Is User is Banned
     *
     * @return bool
     */
    public function getIsBannedAttribute(): bool
    {
        return $this->banDetails !== null;
    }

    /**
     * Get Ban Details
     *
     * @return Ban|null
     */
    public function getBanDetailsAttribute()
    {
        return $this->banDetails;
    }

    /**
     * Get Current User Country
     * @TODO: Implement this in a proper way
     *
     * @return string
     */
    public function getCountryAttribute(): string
    {
        return 'com';
    }

    /**
     * Set the Trait Attribute
     *
     * @param array $accountType
     */
    public function setTraitsAttribute(array $accountType)
    {
        $this->traits = $accountType;
    }

    /**
     * What is this field?
     *
     * @return array
     */
    public function getTraitsAttribute(): array
    {
        if (array_key_exists('rank', $this->attributes) && $this->attributes['rank'] >= 6)
            return ["STAFF"];

        return $this->traits;
    }

    /**
     * Get trusted status with optimized query and caching
     *
     * @return bool
     */
    public function getTrustedAttribute(): bool
    {
        return \Cache::remember("user_trusted_{$this->id}", 1800, function () {
            // Use relationship to avoid N+1 queries
            if (!$this->relationLoaded('security')) {
                $this->load('security');
            }
            
            if ($this->security === null) {
                return true;
            }
            
            $trustedDevices = $this->security->trusted_devices ?? [];
            return in_array($this->attributes['ip_current'], $trustedDevices);
        });
    }

    /**
     * What is this field?
     *
     * @return string
     */
    public function getIdentityTypeAttribute(): string
    {
        return 'HABBO';
    }

    /**
     * We don't care about this, every user is trusted.
     *
     * @return bool
     */
    public function getIdentityVerifiedAttribute(): bool
    {
        return true;
    }

    /**
     * We don't care about this
     *
     * @return int
     */
    public function getLoginLogIdAttribute(): int
    {
        return 1;
    }

    /**
     * We don't care about this
     *
     * @return int
     */
    public function getSessionLoginIdAttribute(): int
    {
        return 1;
    }

    /**
     * Get the HabboClub Attribute
     * In a Retro Habbo everyone is HC, yeah?
     *
     * @WARNING: This is used for Advertisement
     *
     * @return bool
     */
    public function getHabboClubMemberAttribute(): bool
    {
        return Config::get('chocolatey.ads.enabled') == false;
    }

    /**
     * Get the Builders Club Attribute
     * In a Retro Habbo everyone is BC, yeah?
     *
     * @WARNING: This is used for Advertisement
     *
     * @return bool
     */
    public function getBuildersClubMemberAttribute(): bool
    {
        return Config::get('chocolatey.ads.enabled') == false;
    }

    /**
     * Get GTimestamp in Habbo Currency
     *
     * @return string
     */
    public function getAccountCreatedAttribute(): string
    {
        $accountCreated = $this->attributes['account_created'] ?? time();

        return date("Y-m-d", $accountCreated) . 'T' . date("H:i:s.ZZZZ+ZZZZ", $accountCreated);
    }

    /**
     * Get GTimestamp in Habbo Currency
     *
     * @return string
     */
    public function getMemberSinceAttribute(): string
    {
        $accountCreated = $this->attributes['account_created'] ?? time();

        return date("Y-m-d", $accountCreated) . 'T' . date("H:i:s.ZZZZ+ZZZZ", $accountCreated);
    }

    /**
     * Retrieve User Figure String
     *
     * @return string
     */
    public function getFigureStringAttribute(): string
    {
        return $this->attributes['look'] ?? 'hr-115-42.hd-195-19.ch-3030-82.lg-275-1408.fa-1201.ca-1804-64';
    }

    /**
     * Get GTimestamp in Habbo Currency
     *
     * @return false|string
     */
    public function getLastLoginAttribute(): string
    {
        $lastLogin = $this->attributes['last_login'] ?? time();

        return date("Y-m-d", $lastLogin) . 'T' . date("H:i:s.ZZZZ+ZZZZ", $lastLogin);
    }

    /**
     * Get E-Mail Verified Attribute
     *
     * @return bool
     */
    public function getEmailVerifiedAttribute(): bool
    {
        return array_key_exists('mail_verified', $this->attributes)
            ? $this->attributes['mail_verified'] == true : false;
    }
    
    /**
     * Static method to get cached online users count
     *
     * @return int
     */
    public static function getOnlineCount(): int
    {
        return \Cache::remember('users_online_count', 300, function () {
            return static::where('online', 1)->count();
        });
    }
    
    /**
     * Static method to get cached staff members
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getStaffMembers()
    {
        return \Cache::remember('staff_members', 3600, function () {
            return static::select(['id', 'username', 'rank', 'last_online'])
                         ->where('rank', '>=', 6)
                         ->orderBy('rank', 'desc')
                         ->orderBy('last_online', 'desc')
                         ->get();
        });
    }
    
    /**
     * Static method to get user by username with caching
     *
     * @param string $username
     * @return User|null
     */
    public static function getByUsername(string $username)
    {
        return \Cache::remember("user_by_username_{$username}", 1800, function () use ($username) {
            return static::where('username', $username)->first();
        });
    }
    
    /**
     * Get user profile data with minimal queries
     *
     * @return array
     */
    public function getProfileData(): array
    {
        return \Cache::remember("user_profile_data_{$this->id}", 1800, function () {
            // Load necessary relationships in one go
            $this->load(['profile', 'badges' => function ($query) {
                $query->limit(10); // Limit badges for performance
            }, 'friends' => function ($query) {
                $query->where('confirmed', 1)->limit(20);
            }]);
            
            return [
                'id' => $this->id,
                'username' => $this->username,
                'figure' => $this->figureString,
                'motto' => $this->motto ?? '',
                'member_since' => $this->memberSince,
                'last_online' => $this->last_online ?? time(),
                'rank' => $this->rank ?? 1,
                'badges' => $this->badges->pluck('badge_code')->toArray(),
                'friends_count' => $this->friends->count(),
                'profile' => $this->profile ? [
                    'achievements_score' => $this->profile->achievements_score ?? 0,
                    'respect_total' => $this->profile->respect_total ?? 0,
                ] : []
            ];
        });
    }
    
    /**
     * Clear user-related cache when user is updated
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function ($user) {
            \Cache::forget("user_trusted_{$user->id}");
            \Cache::forget("user_profile_data_{$user->id}");
            \Cache::forget("user_by_username_{$user->username}");
            \Cache::forget('users_online_count');
            \Cache::forget('staff_members');
        });
        
        static::deleted(function ($user) {
            \Cache::forget("user_trusted_{$user->id}");
            \Cache::forget("user_profile_data_{$user->id}");
            \Cache::forget("user_by_username_{$user->username}");
            \Cache::forget('users_online_count');
            \Cache::forget('staff_members');
        });
    }
}
