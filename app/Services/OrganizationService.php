<?php

namespace App\Services;

use App\Models\User;
use App\Models\Organization;
use App\DTOs\ParticipantDTO;
use Illuminate\Support\Collection;

class OrganizationService
{
    /**
     * Get all participants within the scope of a Learning Coordinator.
     * Including the LC's own department and all its descendant units.
     */
    public function getParticipantsByLCScope(User $lc): Collection
    {
        $organization = $lc->organization;

        if (!$organization) {
            return collect();
        }

        $participantIds = $this->getAllDescendantUserIds($organization);

        return User::whereIn('id', $participantIds)
            ->where('role', User::ROLE_EMPLOYEE)
            ->with('organization') // Eager load for DTO factory
            ->get()
            ->map(fn($user) => ParticipantDTO::fromModel($user));
    }

    /**
     * Recursive helper to get all user IDs in a node and its children.
     */
    public function getAllDescendantUserIds(Organization $org): array
    {
        $userIds = $org->users()->pluck('id')->toArray();
        
        foreach ($org->children as $child) {
            $userIds = array_merge($userIds, $this->getAllDescendantUserIds($child));
        }

        return $userIds;
    }
}
