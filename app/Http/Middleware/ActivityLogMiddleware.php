<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;

class ActivityLogMiddleware
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log API requests (except GET requests to avoid too much logging)
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            $this->activityLogService->log(
                'api_request',
                "API request: {$request->method()} {$request->path()}",
                auth()->user(),
                null,
                [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'status_code' => $response->getStatusCode(),
                ]
            );
        }

        return $response;
    }
}
