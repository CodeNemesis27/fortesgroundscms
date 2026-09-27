<?php

namespace App\Filament\Client\Resources\Documents\Pages;

use App\Filament\Client\Resources\Documents\DocumentResource;
use App\Services\DocumentVersionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $file = $data['document_upload'] ?? null;
        unset($data['document_upload']);

        abort_unless($file, 422, 'A document file is required.');

        return app(DocumentVersionService::class)->createDocument(
            attributes: $data,
            file: $file,
            owner: auth()->user(),
        );
    }
}
