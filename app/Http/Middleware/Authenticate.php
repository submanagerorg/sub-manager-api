<?php

namespace App\Http\Middleware;

use App\Traits\FormatApiResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use FormatApiResponse;
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards)
    {
        abort($this->formatApiResponse(401, 'Unauthenticated. Proceed to login'));
    }

    // public function handle($request, Closure $next, ...$guards)
    // {
    //     try {
    //         if (empty($guards)) {
    //             $guards = [null];
    //         }
    //         foreach ($guards as $guard) {
    //             $checkGuard = $this->auth->guard($guard)->check();
    //             if ($checkGuard) {
    //                 $this->auth->shouldUse($guard);
    //                 return $next($request);
    //             }
    //         }

    //         return $this->formatApiResponse(403, 'Authentication failed');
    //     } catch(AuthenticationException $e){
    //         return $this->formatApiResponse(403, $e->getMessage());
    //     } catch(Exception $e){
    //         return $this->formatApiResponse(500, $e->getMessage());
    //     } 
    // }
}
