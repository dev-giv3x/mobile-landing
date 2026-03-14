<?php

namespace App\Policies;

use App\Models\Lead;
use MoonShine\Laravel\Models\MoonshineUser;

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
        return $this->isAdmin($user);
    }

    public function update(MoonshineUser $user, Lead $lead): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return (int) $lead->moonshine_user_id === (int) $user->id;
    }

    public function delete(MoonshineUser $user, Lead $lead): bool
    {
        return $this->isAdmin($user);
    }

    private function isAdmin(MoonshineUser $user): bool
    {
        return $user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin';
    }
}
