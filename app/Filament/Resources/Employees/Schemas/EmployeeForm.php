<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Positions\Models\Position;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.en')
                    ->label('Name (EN)')
                    ->required(),
                TextInput::make('name.ar')
                    ->label('Name (AR)')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->rules([
                        fn ($record) => [
                            Rule::unique('users', 'email')->ignore($record?->id),
                        ],
                    ]),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(6),
                TextInput::make('phone')
                    ->required()
                    ->rules([
                        fn ($record) => [
                            Rule::unique('employees', 'phone')->ignore($record?->id),
                            'regex:/^\+?\d{7,15}$/',
                        ],
                    ]),
                Textarea::make('emergency_contact'),
                DatePicker::make('joining_date')
                    ->required(),
                TextInput::make('payroll_start_day')
                    ->label('Payroll Cycle Start Day')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31),
                Select::make('contract_type')
                    ->options([
                        'Full Time' => 'Full Time',
                        'Part Time' => 'Part Time',
                        'Intern' => 'Intern',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Terminated' => 'Terminated',
                        'On Leave' => 'On Leave',
                    ])
                    ->required(),
                TextInput::make('salary')
                    ->numeric()
                    ->required()
                    ->minValue(0),
                TextInput::make('work_start')
                    ->label('Custom Work Start Time')
                    ->type('time')
                    ->nullable(),
                TextInput::make('work_end')
                    ->label('Custom Work End Time')
                    ->type('time')
                    ->nullable(),
                Select::make('position_id')
                    ->relationship('position', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {

                        $position = Position::find($state);
                        $set('department_id', $position->department_id);
                    }),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required(),
                FileUpload::make('documents')
                    ->multiple()
                    ->visibility('public')
                    ->directory('employee-documents')
                    ->disk('public')
                    ->visibility('public')
                    ->reorderable()
                    ->acceptedFileTypes([
                        'image/jpeg',   // jpg, jpeg
                        'image/png',    // png
                        'image/webp',   // webp
                        'application/pdf', // pdf
                        'application/msword', // doc
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
                    ])
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();

                        return Str::slug($originalName) . '-' . rand(1000, 9999) . '.' . $extension;
                    })
                    ->helperText('Allowed file types: jpg, jpeg, png, webp, pdf, doc, docx.'),
            ]);
    }
}
