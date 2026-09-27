<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getRedirectUrl(): string
    {
        $name = Auth::user()->name;
        Notification::make()
            ->success()
            ->title('New project created')
            ->body('A new project has been created by ' . $name)
            ->sendToDatabase(User::whereNot('id', auth()->user()->id)->get());

        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
