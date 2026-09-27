<?php

namespace App\Filament\Resources\Announcements\Pages;

use App\Filament\Resources\Announcements\AnnouncementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnnouncements extends ListRecords
{
    protected static string $resource = AnnouncementResource::class;

    protected ?string $subheading = 'Post and manage company announcements';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
