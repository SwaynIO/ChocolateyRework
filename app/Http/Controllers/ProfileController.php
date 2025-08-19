<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\UserPreferences;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends BaseController
{
    /**
     * Get Public User Data (Optimized)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPublicData(Request $request): JsonResponse
    {
        $username = $request->input('name');
        
        // Single optimized query with eager loading
        $userData = User::select(['id', 'username', 'look', 'motto', 'online', 'last_login'])
                       ->with([
                           'preferences' => function ($query) {
                               $query->select(['user_id', 'profileVisible']);
                           },
                           'selectedBadges' => function ($query) {
                               $query->select(['user_id', 'badge_code', 'slot_id'])
                                    ->where('slot_id', '>', 0)
                                    ->orderBy('slot_id', 'ASC');
                           }
                       ])
                       ->where('username', $username)
                       ->first();

        if (!$userData || $userData->isBanned) {
            return response()->json(['error' => 'User not found or banned'], 404);
        }

        // Check profile visibility
        $profileVisible = !$userData->preferences || $userData->preferences->profileVisible == '1';
        
        if (!$profileVisible) {
            return response()->json(['error' => 'Profile not visible'], 403);
        }

        return response()->json($userData)
                ->header('Cache-Control', 'public, max-age=300'); // 5 minutes cache
    }

    /**
     * Get Public User Profile
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getPublicProfile($userId): JsonResponse
    {
        $userData = User::find($userId);

        if ($userData == null || $userData->isBanned)
            return response()->json(null, 404);

        $userPreferences = UserPreferences::find($userData->uniqueId);

        if ($userPreferences != null && $userPreferences->profileVisible == '0')
            return response()->json(null, 404);

        return response()->json(new UserProfile($userData));
    }

    /**
     * Get Private User Profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfile(Request $request): JsonResponse
    {
        return response()->json(new UserProfile($request->user()));
    }

    /**
     * Get User Stories
     *
     * @TODO: Implement Habbo Stories
     *
     * @return JsonResponse
     */
    public function getStories(): JsonResponse
    {
        return response()->json([]);
    }

    /**
     * Get User Photos (Optimized)
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getPhotos(int $userId): JsonResponse
    {
        // Cache user photos for 10 minutes
        $userPhotos = \Cache::remember("user_photos_{$userId}", 600, function () use ($userId) {
            return Photo::select(['id', 'creator_id', 'url', 'timestamp'])
                       ->where('creator_id', $userId)
                       ->orderBy('timestamp', 'DESC')
                       ->limit(50) // Reasonable limit
                       ->get();
        });

        return response()->json($userPhotos)
                ->header('Cache-Control', 'public, max-age=600'); // 10 minutes cache
    }
}
