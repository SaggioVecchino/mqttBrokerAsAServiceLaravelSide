<?php

namespace App\Http\Middleware;

use Closure;

class UserHasDeviceGroupTopic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $device_group_topic_id)
    {
        try {
            $device_group_topic = Device_group::findOrFail($group_id);
        } catch (ModelNotFoundException $e) {
            return redirect('/projects')->withErrors(['No such link between a group and the topic']);
        }
        $project_id = $device_group_topic->project_id;
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