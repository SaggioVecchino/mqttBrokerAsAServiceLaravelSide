<?php

namespace App\Http\Middleware;

use Closure;

class UserHasTopic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $topic_id)
    {
        try{
            $topic = Device_group::findOrFail($topic_id);
        }catch(ModelNotFoundException $e){
            return redirect('/projects')->withErrors(['Topic doesn\'t exist']);
        }
        $project_id = $topic->project_id;
        try {
            Project_user::where([
                ['user_id', '=', Auth::id()],
                ['project_id', '=', $project_id]
            ])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('/projects')->withErrors(['You don\'t have permission on the topic demanded']);
        }
        return $next($request);
    }
}
