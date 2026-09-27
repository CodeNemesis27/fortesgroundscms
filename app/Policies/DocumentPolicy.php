<?php

namespace App\Policies;

use App\Enums\DocumentPermission;
use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Document $document): bool
    {
        return $this->hasAtLeast($user, $document, DocumentPermission::View);
    }

    public function download(User $user, Document $document): bool
    {
        return $this->hasAtLeast($user, $document, DocumentPermission::Download);
    }

    public function update(User $user, Document $document): bool
    {
        return $this->hasAtLeast($user, $document, DocumentPermission::Edit);
    }

    public function delete(User $user, Document $document): bool
    {
        return $document->owner_id === $user->id || $this->isAdmin($user);
    }

    public function share(User $user, Document $document): bool
    {
        // Only the owner (or an admin) may grant access to others —
        // someone with Edit access on a shared document cannot re-share it.
        return $document->owner_id === $user->id || $this->isAdmin($user);
    }

    private function hasAtLeast(User $user, Document $document, DocumentPermission $minimum): bool
    {
        if ($document->owner_id === $user->id || $this->isAdmin($user)) {
            return true;
        }

        return $document->activeShares()
            ->where('shared_with_user_id', $user->id)
            ->get()
            ->contains(fn($share) => $share->permission->atLeast($minimum));
    }

    private function isAdmin(User $user): bool
    {
        // Swap this out for your real role/permission system
        // (Spatie Permission, a `role` column, Gates, etc).
        return method_exists($user, 'isAdmin') && $user->isAdmin();
    }
}
