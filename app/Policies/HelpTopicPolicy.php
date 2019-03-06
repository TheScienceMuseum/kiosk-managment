<?php

namespace App\Policies;

use App\User;
use App\HelpTopic;
use Illuminate\Auth\Access\HandlesAuthorization;

class HelpTopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the help topic.
     *
     * @param  \App\User  $user
     * @param  \App\HelpTopic  $helpTopic
     * @return mixed
     */
    public function update(User $user, HelpTopic $helpTopic)
    {
        return $user->can('edit all help topics');
    }
}
