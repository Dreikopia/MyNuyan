<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ComplaintPolicy
{
    public function view(User $user, Complaint $complaint)
    {
        return $complaint->user_id === $user->id ? Response::allow() : Response::denyAsNotFound();
    }

    public function create(User $user): bool
    {
        return true;
    }
}
