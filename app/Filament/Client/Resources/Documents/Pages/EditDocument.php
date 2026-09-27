<?php

namespace App\Filament\Client\Resources\Documents\Pages;

use App\Enums\AuditEvent;
use App\Filament\Client\Resources\Documents\DocumentResource;
use App\Services\DocumentAuditLogger;
use App\Services\DocumentVersionService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    public function getHeading(): string
    {
        return 'Edit ' . $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(fn(Model $record) => app(DocumentAuditLogger::class)->log(AuditEvent::Deleted, $record)),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $file = $data['document_upload'] ?? null;
        unset($data['document_upload']);

        $record->update($data);

        if ($record->wasChanged()) {
            app(DocumentAuditLogger::class)->log(
                AuditEvent::Updated,
                $record,
                ['changed_fields' => array_keys($record->getChanges())]
            );
        }

        if ($file) {
            app(DocumentVersionService::class)->addVersion(
                document: $record,
                file: $file,
                uploader: auth()->user(),
                notes: 'Updated via edit form',
            );
        }

        return $record->fresh();
    }
}
