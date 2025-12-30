<?php

namespace App\Filament\Pages;

use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\File;
use Modules\Settings\Models\Setting;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-cog-6-tooth';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            $this->getRecord()?->toArray() ?? []
        );
    }

    protected function getFormSchema(): array
    {
        $currencies = json_decode(
            File::get(
                base_path('vendor/umpirsky/currency-list/data/en/currency.json')
            ),
            true
        );

        return [
            TextInput::make('name')
                ->label('Site Name')
                ->required()
                ->maxLength(255)
                ->nullable(),

            FileUpload::make('logo')
                ->label('Site Logo')
                ->directory('settings')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->nullable(),

            TextInput::make('phone')
                ->label('Phone Number')
                ->tel()
                ->nullable(),

            TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->nullable(),

            TextInput::make('address')
                ->label('Address')
                ->maxLength(500)
                ->nullable(),

            TextInput::make('tax_number')
                ->label('Tax Number')
                ->maxLength(50)
                ->nullable(),

            Select::make('language')
                ->label('Default Language')
                ->options([
                    'en' => 'English',
                    'ar' => 'Arabic',
                ])
                ->nullable(),

            Select::make('currency')
                ->label('Currency')
                ->options($currencies)
                ->searchable()
                ->nullable(),

            TextInput::make('tax')
                ->label('Tax %')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->nullable(),

            Select::make('default_printable_language')
                ->label('Default Invoice/Quotation Language')
                ->options([
                    null => 'None',
                    'en' => 'English',
                    'ar' => 'Arabic',
                ])
                ->nullable(),

            TextInput::make('break_minutes')
                ->label('Default Break Minutes')
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->nullable(),

            TextInput::make('overtime_minutes')
                ->label('Default Overtime Minutes')
                ->numeric()
                ->minValue(0)
                ->default(30)
                ->nullable(),

            TextInput::make('days_off_limit')
                ->label('Days Off Limit')
                ->numeric()
                ->minValue(0)
                ->default(5)
                ->nullable(),

            TextInput::make('encashment_limit')
                ->label('Encashment Limit')
                ->numeric()
                ->minValue(0)
                ->default(2)
                ->nullable(),

            TextInput::make('working_days_per_month')
                ->label('Working Days Per Month')
                ->numeric()
                ->minValue(0)
                ->default(20)
                ->nullable(),

            Select::make('overtime_type')
                ->label('Overtime Type')
                ->options([
                    'Percentage' => 'Percentage',
                    'Fixed' => 'Fixed',
                ])
                ->default('Percentage'),

            TextInput::make('overtime_value')
                ->label('Overtime Value')
                ->numeric()
                ->minValue(0)
                ->default(1.5)
                ->nullable(),

            TextInput::make('default_work_from')
                ->label('Default Work From')
                ->type('time')
                ->default('09:00')
                ->nullable(),

            TextInput::make('default_work_to')
                ->label('Default Work To')
                ->type('time')
                ->default('17:00')
                ->nullable(),

            TextInput::make('grace_period_minutes')
                ->label('Grace Period Minutes')
                ->numeric()
                ->minValue(0)
                ->default(15)
                ->nullable(),

            Repeater::make('work_type_days')
                ->label('Work Schedule per Contract Type')
                ->schema([
                    Select::make('type')
                        ->label('Contract Type')
                        ->options([
                            'Full Time' => 'Full Time',
                            'Part Time' => 'Part Time',
                            'Intern' => 'Intern',
                        ])
                        ->required()
                        ->distinct()
                        ->disableOptionWhen(function ($value, $state, $get) {
                            $selectedTypes = collect($get('../../work_type_days'))
                                ->pluck('type')
                                ->filter()
                                ->toArray();

                            return in_array($value, $selectedTypes) && $value !== $state;
                        })
                        ->live(),

                    Select::make('days')
                        ->label('Working Days')
                        ->multiple()
                        ->options([
                            'Saturday' => 'Saturday',
                            'Sunday' => 'Sunday',
                            'Monday' => 'Monday',
                            'Tuesday' => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday' => 'Thursday',
                            'Friday' => 'Friday',
                        ])
                        ->required(),
                ])
                ->columns(2)
                ->maxItems(3),

            Section::make('Work Hours & Overtime')
                ->schema([
                    Select::make('overtime_active_mode')
                        ->label('Current Overtime Calculation Method')
                        ->options([
                            'percentage' => 'Percentage of Hourly Salary',
                            'fixed' => 'Fixed Hourly Rate',
                        ])
                        ->reactive(),

                    TextInput::make('overtime_percentage')
                        ->label('Overtime Percentage (e.g., 1.5)')
                        ->numeric()
                        ->default(1.5),

                    TextInput::make('overtime_fixed_rate')
                        ->label('Overtime Fixed Rate (Per Hour)')
                        ->numeric()
                        ->default(0)
                        ->prefix('$'),
                ])->columns(3),

            Section::make('Office Location & Geofencing')
                ->description('Pin your office on the map and set the allowed radius for attendance.')
                ->schema([
                    Map::make('location')
                        ->label('Select Office Location')
                        ->columnSpanFull()
                        ->defaultLocation(latitude: 30.0444, longitude: 31.2357)
                        ->showMyLocationButton(true)
                        ->afterStateHydrated(function ($state, $record, Set $set): void {
                            // Load existing data from DB into the map fields
                            $set('location', [
                                'lat' => $record?->company_latitude,
                                'lng' => $record?->company_longitude
                            ]);
                        })
                        ->afterStateUpdated(function (Set $set, ?array $state): void {
                            // Update the actual DB columns when marker moves
                            $set('company_latitude', $state['lat']);
                            $set('company_longitude', $state['lng']);
                        })
                        // Circle Radius Integration
                        ->rangeSelectField('radius_meter') // Dynamic binding to radius input
                        ->setFilledColor('#cad9ec')
                        ->markerColor('#3b82f6')
                        ->draggable(true)
                        ->clickable(true)
                        ->zoom(15)
                        ->showMyLocationButton(true)
                        ->extraStyles([
                            'min-height: 45vh',
                            'border-radius: 12px'
                        ]),

                    Grid::make(3)
                        ->schema([
                            TextInput::make('company_latitude')
                                ->label('Latitude')
                                ->numeric()
                                ->readOnly(),

                            TextInput::make('company_longitude')
                                ->label('Longitude')
                                ->numeric()
                                ->readOnly(),

                            TextInput::make('radius_meter')
                                ->label('Allowed Radius (Meters)')
                                ->numeric()
                                ->default(100)
                                ->suffix('m')
                                ->live() // Essential: re-draws the map circle instantly as you type,
                        ])
                ])
        ];
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord() ?? new Setting();
        $record->fill($data);
        $record->save();

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }

    public function getRecord(): ?Setting
    {
        return Setting::query()->first();
    }
}
