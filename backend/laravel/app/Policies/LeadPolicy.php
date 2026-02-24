<?php

namespace App\Policies;

use App\Models\Lead;
use MoonShine\Laravel\Models\MoonshineUser;
use Illuminate\Auth\Access\Response;

class LeadPolicy
{
    public function viewAny(MoonshineUser $user): bool
    {
        return true;
    }


    public function view(MoonshineUser $user, Lead $lead): bool
    {
        return true;
    }


    public function create(MoonshineUser $user): bool
    {
        return $user->isSuperUser();
    }


    public function update(MoonshineUser $user, Lead $lead): bool
    {
        return $user->isSuperUser();
    }


    public function delete(MoonshineUser $user, Lead $lead): bool
    {
        return $user->isSuperUser();
    }
}
