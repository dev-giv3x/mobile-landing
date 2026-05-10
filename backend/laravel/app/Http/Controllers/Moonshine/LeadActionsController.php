<?php

declare(strict_types=1);

namespace App\Http\Controllers\Moonshine;

use App\Models\Lead;
use App\MoonShine\Resources\Lead\LeadResource;
use Illuminate\Http\RedirectResponse;

class LeadActionsController
{
    public function accept(Lead $lead): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            abort(403);
        }

        if ($this->isAdmin($user)) {
            abort(403);
        }

        if ($lead->status !== 'new') {
            return redirect()->back();
        }

        $lead->update([
            'status' => 'in_process',
            'moonshine_user_id' => $user->id,
        ]);

        return redirect(app(LeadResource::class)->getDetailPageUrl($lead->getKey()));
    }

    private function isAdmin($user): bool
    {
        return $user->isSuperUser() || $user->moonshineUserRole?->name === 'Admin';
    }
}
