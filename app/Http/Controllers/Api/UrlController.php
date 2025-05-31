<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Services\UrlService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{

    protected $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    /**
     * Create a new short URL.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_url' => 'required|url|max:2048',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
            'custom_code' => 'nullable|string|min:4|max:10|alpha_num|unique:urls,short_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $url = $this->urlService->createShortUrl(
                $request->input('original_url'),
                $request->input('expires_in_days'),
                $request->input('custom_code')
            );

            if (!$url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create shortened URL',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $url->id,
                    'original_url' => $url->original_url,
                    'short_code' => $url->short_code,
                    'short_url' => $this->urlService->getFullShortUrl($url),
                    'expires_at' => $url->expires_at,
                    'is_active' => $url->is_active,
                    'created_at' => $url->created_at,
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get URL details.
     *
     * @param string $shortCode
     * @return JsonResponse
     */
    public function get(string $shortCode)
    {
        try {

            $url = Url::where('short_code', $shortCode)->first();

            if (!$url) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL not found',
                ], 404);
            }


            if (Auth::check() && $url->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $url->id,
                    'original_url' => $url->original_url,
                    'short_code' => $url->short_code,
                    'short_url' => $this->urlService->getFullShortUrl($url),
                    'expires_at' => $url->expires_at,
                    'is_active' => $url->is_active,
                    'click_count' => $url->click_count,
                    'created_at' => $url->created_at,
                    'updated_at' => $url->updated_at,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all URLs for authenticated user.
     *
     * @return JsonResponse
     */
    public function list()
    {
        try {
            $urls = $this->urlService->getUrlsForUser(Auth::id());

            $formattedUrls = $urls->map(function ($url) {
                return [
                    'id' => $url->id,
                    'original_url' => $url->original_url,
                    'short_code' => $url->short_code,
                    'short_url' => $this->urlService->getFullShortUrl($url),
                    'expires_at' => $url->expires_at,
                    'is_active' => $url->is_active,
                    'click_count' => $url->click_count,
                    'created_at' => $url->created_at,
                    'updated_at' => $url->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedUrls
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing URL.
     *
     * @param Request $request
     * @param Url $url
     * @return JsonResponse
     */
    public function update(Request $request, Url $url)
    {
        // Check ownership
        if ($url->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'original_url' => 'nullable|url|max:2048',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($this->urlService->updateUrl($url, $request->only(['original_url', 'expires_in_days', 'is_active']))) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $url->id,
                        'original_url' => $url->original_url,
                        'short_code' => $url->short_code,
                        'short_url' => $this->urlService->getFullShortUrl($url),
                        'expires_at' => $url->expires_at,
                        'is_active' => $url->is_active,
                        'click_count' => $url->click_count,
                        'created_at' => $url->created_at,
                        'updated_at' => $url->updated_at,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to update URL',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an existing URL.
     *
     * @param Url $url
     * @return JsonResponse
     */
    public function delete(Url $url)
    {
        // Check ownership
        if ($url->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            if ($this->urlService->deleteUrl($url)) {
                return response()->json([
                    'success' => true,
                    'message' => 'URL deleted successfully',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete URL',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get view for a URL.
     *
     * @param Url $url
     * @return JsonResponse
     */
    public function view(Url $url)
    {
        // Check ownership
        if ($url->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $url->load('visits');

            $views = [
                'total_clicks' => $url->click_count,
                'is_active' => $url->is_active,
                'expires_at' => $url->expires_at,
                'visits' => $url->visits->map(function ($visit) {
                    return [
                        'id' => $visit->id,
                        'ip_address' => $visit->ip_address,
                        'user_agent' => $visit->user_agent,
                        'referrer' => $visit->referrer,
                        'created_at' => $visit->created_at,
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'data' => $views
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
