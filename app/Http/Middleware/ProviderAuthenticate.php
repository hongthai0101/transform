<?php

namespace App\Http\Middleware;

use App\Models\Provider;
use App\Models\Transform;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ProviderAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        $providerSecret = $request->headers->get('X-Provider-Secret');
        $pathArr = explode('/', $request->path());
        $code = Arr::get($pathArr, 1);
        if (!$code) {
            return response()->json(['error' => 'Provider not found'], 404);
        }

        $provider = Provider::where([
            'code' => $code,
            'is_active' => true,
        ])->first();

        if (!$provider) {
            return response()->json(['error' => 'Provider not found'], 404);
        }

        if ($provider->is_authenticate && (!$provider->secret || $provider->secret !== $providerSecret)) {
            return response()->json(['error' => 'Provider secret not match'], 401);
        }

        $transformCode = Arr::get($pathArr, 2);
        if (!$transformCode) {
            return response()->json(['error' => 'Transform not found'], 404);
        }

        $transform = Transform::where('code', $transformCode)->first();
        if (!$transform) {
            return response()->json(['error' => 'Transform not found'], 404);
        }

        $request->merge([
            'provider' => $provider,
            'transform' => $transform,
        ]);
        return $next($request);
    }
}
