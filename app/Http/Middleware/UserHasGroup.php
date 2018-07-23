<?php

namespace App\Http\Middleware;

use Closure;
use App\Device_group;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Project_user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserHasGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $group_id)
    {
        try{
            $group = Device_group::findOrFail($group_id);
        }catch(ModelNotFoundException $e){
            return redirect('/projects')->withErrors(['Group doesn\'t exist']);
        }
        $project_id = $group->project_id;
        try {
            Project_user::where([
                ['user_id', '=', Auth::id()],
                ['project_id', '=', $project_id]
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('/projects')->withErrors(['You don\'t have permission on the group demanded']);
        }
        return $next($request);
    }
}
