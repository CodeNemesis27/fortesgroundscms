<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Hidden::make('employee_code')
                        ->dehydrated()
                        ->default(function () {
                            $lastEmployee = Employee::orderByDesc('id')->first();

                            if (!$lastEmployee) {
                                return 'EMP-001';
                            }

                            $lastNumber = (int) str_replace('EMP-', '', $lastEmployee->employee_code);

                            return 'EMP-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        })
                        ->disabled(),

                    Section::make('Employee details')
                        ->description('Basic information identifying the employee')
                        ->columns(2)
                        ->schema([
                            TextInput::make('first_name')
                                ->required()
                                ->placeholder('e.g. Richard Bryan'),
                            TextInput::make('last_name')
                                ->placeholder('e.g. Sombrio')
                                ->required(),
                            TextInput::make('position')
                                ->required()
                                ->placeholder('e.g. Project manager'),
                            Select::make('employee_type')
                                ->native(false)
                                ->options([
                                    'Regular' => 'Regular',
                                    'Contractual' => 'Contractual',
                                    'Project based' => 'Project based'
                                ])
                                ->required(),
                            DatePicker::make('date_hired')
                                ->required()
                                ->default(now()),
                            TextInput::make('contact_no')
                                ->required()
                                ->placeholder('e.g. 09156119397'),
                            TextInput::make('address')
                                ->required()
                                ->placeholder('Enter employee complete address')
                                ->columnSpanFull(),
                            ToggleButtons::make('status')
                                ->inline()
                                ->default('Active')
                                ->columnSpanFull()
                                ->options([
                                    'Active' => 'Active',
                                    'On-leave' => 'On-leave',
                                    'Terminated' => 'Terminated'
                                ])
                                ->required(),
                        ]),
                ])->columnSpan(7),

                Group::make([
                    Section::make('Compensation')
                        ->description('Pay rate and how it is calculated')
                        ->columns(2)
                        ->schema([
                            TextInput::make('basic_rate')
                                ->required()
                                ->numeric()
                                ->prefix('PHP')
                                ->placeholder('0'),
                            Select::make('rate_type')
                                ->native(false)
                                ->options([
                                    'Hourly' => 'Hourly',
                                    'Daily' => 'Daily',
                                    'Monthly' => 'Monthly'
                                ])
                                ->required(),
                        ]),

                    Section::make('Account credentials')
                        ->hiddenOn(Operation::Edit)
                        ->description('Login email and password for portal access')
                        ->schema([
                            TextInput::make('user.email')
                                ->label('Email address')
                                ->email()
                                ->required()
                                ->unique(
                                    table: 'users',
                                    column: 'email',
                                    ignoreRecord: true,
                                )
                                ->placeholder('e.g. richard_sombrio@gmail.com'),
                            TextInput::make('user.password')
                                ->label('Password')
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->placeholder('••••••••')
                                ->same('password_confirmation')
                                ->rules([
                                    'min:8',
                                    'regex:/[a-zA-Z]/',       // must contain at least one letter
                                    'regex:/[0-9]/',           // must contain at least one number
                                    'regex:/[@$!%*#?&^_\-]/', // must contain at least one symbol
                                    'regex:/[A-Z]/',           // must contain at least one uppercase letter
                                    Password::min(8)
                                        ->letters()
                                        ->mixedCase()
                                        ->numbers()
                                        ->symbols()
                                        ->uncompromised(),
                                ])
                                ->validationMessages([
                                    'min' => 'Password must be at least 8 characters.',
                                    'regex' => 'Password must contain letters, numbers, symbols, and at least one uppercase letter.',
                                    'password.uncompromised' => 'This password has appeared in a data breach. Please choose a different password.',
                                ]),
                            TextInput::make('password_confirmation')
                                ->password()
                                ->placeholder('Confirm password')
                                ->revealable()
                                ->same('user.password')
                                ->requiredWith('user.password')
                                ->label('Confirm password')
                                ->dehydrated(false),
                        ]),
                ])->columnSpan(5)

            ])->columns(12);
    }
}
