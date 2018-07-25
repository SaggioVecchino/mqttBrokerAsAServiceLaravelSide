<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Project_user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserHasProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $project_id)
    {
        try {
            Project_user::where([
                ['user_id', '=', Auth::id()],
                ['project_id', '=', $project_id]
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            if ($request->wantsJson())
                return response(['errors'=>["otherError"=>['You don\'t have permission on the project with ID: ' . $project_id] ]] ,403);
            return redirect('/projects')->withErrors(['You don\'t have permission on the project with ID: ' . $project_id]);
        }
        return $next($request);
    }
}
