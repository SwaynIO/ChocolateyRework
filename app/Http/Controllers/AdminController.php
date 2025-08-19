<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Ban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

/**
 * Class AdminController
 * Administration panel controller for rank 7+ users
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard with optimized queries
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Use single query with conditional counting for better performance
        $stats = \DB::select("
            SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN online = 1 THEN 1 ELSE 0 END) as online_users,
                SUM(CASE WHEN rank >= 7 THEN 1 ELSE 0 END) as admin_users,
                SUM(CASE WHEN rank >= 6 THEN 1 ELSE 0 END) as staff_users
            FROM users
        ")[0];

        // Get banned users count separately (different table)
        $stats->banned_users = Ban::count();
        
        // Get articles count with caching
        $stats->total_articles = \Cache::remember('articles_count', 300, function () {
            return Article::count();
        });

        // Recent activity for dashboard
        $recentUsers = User::select(['id', 'username', 'last_login', 'account_created'])
                          ->orderBy('account_created', 'desc')
                          ->limit(5)
                          ->get();

        $recentArticles = Article::select(['id', 'title', 'timestamp'])
                               ->orderBy('timestamp', 'desc')
                               ->limit(5)
                               ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentArticles'));
    }

    /**
     * Display users management page with optimized queries
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        // Optimize query by selecting only needed fields
        $query = User::select([
            'id', 'username', 'mail', 'rank', 'credits', 'pixels', 'points',
            'online', 'last_login', 'account_created', 'look'
        ]);

        // Search functionality with index optimization
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            
            // If search is numeric, search by ID first (faster)
            if (is_numeric($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('id', $search)
                      ->orWhere('username', 'LIKE', "%{$search}%")
                      ->orWhere('mail', 'LIKE', "%{$search}%");
                });
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('username', 'LIKE', "%{$search}%")
                      ->orWhere('mail', 'LIKE', "%{$search}%");
                });
            }
        }

        // Filter by rank (indexed column)
        if ($request->has('rank') && $request->rank !== '') {
            $query->byRank($request->rank);
        }

        // Filter by banned status using optimized approach
        if ($request->has('banned') && $request->banned !== '') {
            if ($request->banned === '1') {
                // Use EXISTS for better performance
                $query->whereExists(function ($subQuery) {
                    $subQuery->select(\DB::raw(1))
                            ->from('bans')
                            ->whereColumn('bans.user_id', 'users.id');
                });
            } else {
                $query->whereNotExists(function ($subQuery) {
                    $subQuery->select(\DB::raw(1))
                            ->from('bans')
                            ->whereColumn('bans.user_id', 'users.id');
                });
            }
        }

        // Order by indexed column and paginate efficiently
        $users = $query->orderBy('id', 'desc')
                      ->paginate(50, ['*'], 'page', $request->get('page', 1));

        // Eager load ban details only for users that might be banned
        if ($users->count() > 0) {
            $users->load(['banDetails' => function ($query) {
                $query->select(['id', 'user_id', 'reason', 'timestamp', 'expires']);
            }]);
        }

        return view('admin.users', compact('users'));
    }

    /**
     * Show user details
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function userDetails($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-details', compact('user'));
    }

    /**
     * Update user data
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'mail' => 'required|email|max:255',
            'rank' => 'required|integer|min:1|max:7',
            'credits' => 'nullable|integer|min:0',
            'pixels' => 'nullable|integer|min:0',
            'points' => 'nullable|integer|min:0',
            'motto' => 'nullable|string|max:255',
        ]);

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    /**
     * Ban a user
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function banUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent banning admin users
        if ($user->rank >= 7) {
            return response()->json(['success' => false, 'message' => 'Cannot ban admin users'], 403);
        }

        $data = $request->validate([
            'reason' => 'required|string|max:255',
            'expires' => 'nullable|integer',
        ]);

        $ban = new Ban();
        $ban->user_id = $user->id;
        $ban->reason = $data['reason'];
        $ban->banned_by = $request->user()->id;
        $ban->timestamp = time();
        
        if (isset($data['expires'])) {
            $ban->expires = $data['expires'];
        }
        
        $ban->save();

        return response()->json(['success' => true, 'message' => 'User banned successfully']);
    }

    /**
     * Unban a user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        Ban::where('user_id', $user->id)->delete();

        return response()->json(['success' => true, 'message' => 'User unbanned successfully']);
    }

    /**
     * Display articles management
     *
     * @return \Illuminate\View\View
     */
    public function articles()
    {
        $articles = Article::orderBy('id', 'desc')->paginate(20);
        return view('admin.articles', compact('articles'));
    }

    /**
     * Create new article
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createArticle(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'nullable|string|max:255',
        ]);

        $data['author_id'] = $request->user()->id;
        $data['timestamp'] = time();

        Article::create($data);

        return response()->json(['success' => true, 'message' => 'Article created successfully']);
    }

    /**
     * Delete article
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteArticle($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(['success' => true, 'message' => 'Article deleted successfully']);
    }

    /**
     * Display system settings
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Display admin logs
     *
     * @return \Illuminate\View\View
     */
    public function logs()
    {
        // This would require a logs table implementation
        return view('admin.logs');
    }
}