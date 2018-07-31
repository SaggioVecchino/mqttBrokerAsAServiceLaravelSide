<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;

class UserExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $user_id)
    {
        try {
            User::where([
                ['id', '=', $user_id]
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            if ($request->wantsJson())
                return response([
                    'errors' => [
                        "otherError" => [
                            "the user you're trying to deal with doesn't exist ::: ***..***"
                        ]
                    ]
                ], 403);
            return redirect('/projects')->withErrors(["the user you're trying to deal with doesn't exist"]);
        }
        return $next($request);
    }
}