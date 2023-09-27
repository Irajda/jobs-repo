<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function access_update(User $user, Job $job)
    {
        return $job->jobs_assignment()->where('user_id', '=', $user->id)->exists()
            ? Response::allow() : Response::denyWithStatus(status: 403, message: 'You do not have permission to update this job.');
    }

    public function access_delete(User $user, Job $job)
    {
        return $job->jobs_assignment->isEmpty()
            ? Response::allow() : Response::denyWithStatus(status: 403, message: 'Deletion is not allowed for active jobs.');
    }

}
