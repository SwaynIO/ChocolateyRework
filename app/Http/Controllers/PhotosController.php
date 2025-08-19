<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoLike;
use App\Models\PhotoReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class PhotosController
 * @package App\Http\Controllers
 */
class PhotosController extends BaseController
{
    /**
     * Render a set of Public HabboWEB Photos (Optimized)
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        // Cache public photos for 5 minutes
        $photos = \Cache::remember('public_photos', 300, function () {
            return Photo::select(['id', 'creator_id', 'url', 'timestamp'])
                       ->with(['creator' => function ($query) {
                           $query->select(['id', 'username']);
                       }])
                       ->whereNotExists(function ($query) {
                           $query->select(\DB::raw(1))
                                ->from('photo_reports')
                                ->whereColumn('photo_reports.photo_id', 'photos.id')
                                ->where('status', 1); // Exclude approved reports
                       })
                       ->orderBy('timestamp', 'DESC')
                       ->limit(100) // Reasonable limit for performance
                       ->get();
        });

        return response()->json($photos, 200, [], JSON_UNESCAPED_SLASHES)
                ->header('Cache-Control', 'public, max-age=300'); // 5 minutes cache
    }

    /**
     * Register a Report of a Photo
     * Observation.: We will not create a limit of max reports.
     * Since it's a retro we don't really care about reports.
     *
     * @MODERATION: Reporting Status (0 = Not Reviewed, 1 = Report Approved, 2 = Report Not Approved
     *
     * @param Request $request
     * @param int $photoId
     * @return JsonResponse
     */
    public function report(Request $request, int $photoId): JsonResponse
    {
        (new PhotoReport)->store($photoId, $request->json()->get('reason'), $request->user()->uniqueId)->save();

        return response()->json('');
    }

    /**
     * Like a Photo (Optimized)
     *
     * @param Request $request
     * @param int $photoId
     * @return JsonResponse
     */
    public function likePhoto(Request $request, int $photoId): JsonResponse
    {
        $userId = $request->user()->id;
        $username = $request->user()->username;
        
        // Check if already liked using EXISTS (faster than count)
        $alreadyLiked = \DB::selectOne(
            "SELECT 1 FROM photo_likes WHERE username = ? AND photo_id = ? LIMIT 1",
            [$username, $photoId]
        );
        
        if ($alreadyLiked) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        // Use insert instead of model for better performance
        \DB::insert(
            "INSERT INTO photo_likes (photo_id, username, user_id, timestamp) VALUES (?, ?, ?, ?)",
            [$photoId, $username, $userId, time()]
        );

        // Clear cache for this photo's likes
        \Cache::forget("photo_likes_{$photoId}");

        return response()->json(['message' => 'Photo liked'], 200);
    }

    /**
     * Unlike a Photo
     *
     * @param Request $request
     * @param int $photoId
     * @return JsonResponse
     */
    public function unlikePhoto(Request $request, int $photoId): JsonResponse
    {
        if (PhotoLike::where('username', $request->user()->name)->where('photo_id', $photoId)->count() == 0)
            return response()->json('');

        PhotoLike::where('username', $request->user()->name)->where('photo_id', $photoId)->delete();

        return response()->json('');
    }

    /**
     * Delete a Photo
     *
     * @param Request $request
     * @param int $photoId
     * @return Response
     */
    public function delete(Request $request, int $photoId): Response
    {
        $photo = Photo::find($photoId);

        if ($photo == null || $photo->creator_id != $request->user()->uniqueId)
            return response('', 401);

        $photo->delete();

        return response('');
    }
}
