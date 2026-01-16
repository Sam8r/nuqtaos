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
                    ->label(__('employees.fields.name_en'))
                    ->required(),
                TextInput::make('name.ar')
                    ->label(__('employees.fields.name_ar'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('employees.fields.email'))
                    ->email()
                    ->required()
                    ->rules([
                        fn ($record) => [
                            Rule::unique('users', 'email')->ignore($record?->id),
                        ],
                    ]),
                TextInput::make('password')
                    ->label(__('employees.fields.password'))
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(6),
                TextInput::make('phone')
                    ->label(__('employees.fields.phone'))
                    ->required()
                    ->rules([
                        fn ($record) => [
                            Rule::unique('employees', 'phone')->ignore($record?->id),
                            'regex:/^\+?\d{7,15}$/',
                        ],
                    ]),
                Textarea::make('emergency_contact')
                    ->label(__('employees.fields.emergency_contact')),
                DatePicker::make('joining_date')
                    ->label(__('employees.fields.joining_date'))
                    ->required(),
                TextInput::make('payroll_start_day')
                    ->label(__('employees.fields.payroll_start_day'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(31),
                Select::make('contract_type')
                    ->label(__('employees.fields.contract_type'))
                    ->options([
                        'Full Time' => __('employees.contract_types.Full Time'),
                        'Part Time' => __('employees.contract_types.Part Time'),
                        'Intern' => __('employees.contract_types.Intern'),
                    ])
                    ->required(),
                Select::make('work_type')
                    ->label(__('employees.fields.work_type'))
                    ->options([
                        'Onsite' => __('employees.work_types.Onsite'),
                        'Remote' => __('employees.work_types.Remote'),
                    ])
                    ->required(),
                Select::make('status')
                    ->label(__('employees.fields.status'))
                    ->options([
                        'Active' => __('employees.statuses.Active'),
                        'Inactive' => __('employees.statuses.Inactive'),
                        'Terminated' => __('employees.statuses.Terminated'),
                        'On Leave' => __('employees.statuses.On Leave'),
                    ])
                    ->required(),
                TextInput::make('salary')
                    ->label(__('employees.fields.salary'))
                    ->numeric()
                    ->required()
                    ->minValue(0),
                TextInput::make('work_start')
                    ->label(__('employees.fields.work_start'))
                    ->type('time')
                    ->nullable(),
                TextInput::make('work_end')
                    ->label(__('employees.fields.work_end'))
                    ->type('time')
                    ->nullable(),
                Select::make('position_id')
                    ->label(__('employees.fields.position'))
                    ->relationship('position', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {

                        $position = Position::find($state);
                        $set('department_id', $position->department_id);
                    }),
                Select::make('department_id')
                    ->label(__('employees.fields.department'))
                    ->relationship('department', 'name')
                    ->required(),
                FileUpload::make('documents')
                    ->label(__('employees.fields.documents'))
                    ->multiple()
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
                    ->helperText(__('employees.helper_texts.documents')),
            ]);
    }
}
