<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\HtmlString;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name'     => trim($data['first_name'] . ' ' . $data['last_name']),
            'email'    => $data['user']['email'],
            'password' => $data['user']['password'],
            'role' => 'Employee'
        ]);

        $data['user_id'] = $user->id;
        unset($data['user']);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->record->user->update(array_filter([
            'name'     => trim($data['first_name'] . ' ' . $data['last_name']),
            'email'    => $data['user']['email'],
            'password' => $data['user']['password'] ?? null, // null if left blank on edit
        ]));

        unset($data['user']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $recipient = auth()->user();
        $newEmployee = $this->record;

        Notification::make()
            ->title('New employee created!')
            ->body(new HtmlString("<strong>{$newEmployee->name}</strong> has been created as a new employee"))
            ->sendToDatabase($recipient);
    }
}
