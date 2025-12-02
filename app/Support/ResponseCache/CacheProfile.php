<?php

namespace App\Support\ResponseCache;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

class CacheProfile extends CacheAllSuccessfulGetRequests
{
    public function shouldCacheRequest(Request $request): bool
    {
        // Only cache for guests. Logged in users get fresh content (to show correct header, etc).
        if (Auth::check()) {
            return false;
        }

        return parent::shouldCacheRequest($request);
    }
}
