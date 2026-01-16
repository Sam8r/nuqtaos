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

    public static function getNavigationLabel(): string
    {
        return __('settings.page.title');
    }

    public function getTitle(): string
    {
        return __('settings.page.title');
    }

    public function mount(): void
    {
        $this->form->fill(
            $this->getRecord()?->toArray() ?? []
        );
    }

    protected function getFormSchema(): array
    {
        $locale = strtolower(str_replace('_', '-', app()->getLocale() ?? 'en'));
        $primaryLocale = explode('-', $locale)[0];

        $currencyPath = base_path("vendor/umpirsky/currency-list/data/{$primaryLocale}/currency.json");

        if (! File::exists($currencyPath)) {
            $currencyPath = base_path('vendor/umpirsky/currency-list/data/en/currency.json');
        }

        $currencies = json_decode(File::get($currencyPath), true);

        return [
            TextInput::make('name')
                ->label(__('settings.fields.name'))
                ->required()
                ->maxLength(255)
                ->nullable(),

            FileUpload::make('logo')
                ->label(__('settings.fields.logo'))
                ->directory('settings')
                ->image()
                ->disk('public')
                ->visibility('public')
                ->nullable(),

            TextInput::make('phone')
                ->label(__('settings.fields.phone'))
                ->tel()
                ->nullable(),

            TextInput::make('email')
                ->label(__('settings.fields.email'))
                ->email()
                ->nullable(),

            TextInput::make('address')
                ->label(__('settings.fields.address'))
                ->maxLength(500)
                ->nullable(),

            TextInput::make('tax_number')
                ->label(__('settings.fields.tax_number'))
                ->maxLength(50)
                ->nullable(),

            Select::make('language')
                ->label(__('settings.fields.language'))
                ->options(collect(__('settings.languages'))
                    ->mapWithKeys(fn ($label, $value) => [$value => $label])
                    ->toArray())
                ->nullable(),

            Select::make('currency')
                ->label(__('settings.fields.currency'))
                ->options($currencies)
                ->searchable()
                ->nullable(),

            Select::make('salary_currency')
                ->label(__('settings.fields.salary_currency'))
                ->options($currencies)
                ->searchable()
                ->nullable(),

            TextInput::make('tax')
                ->label(__('settings.fields.tax'))
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->nullable(),

            Select::make('default_printable_language')
                ->label(__('settings.fields.default_printable_language'))
                ->options(
                    [null => __('settings.options.invoice_languages.none')]
                    + collect(__('settings.languages'))
                        ->mapWithKeys(fn ($label, $value) => [$value => $label])
                        ->toArray()
                )
                ->nullable(),

            TextInput::make('break_minutes')
                ->label(__('settings.fields.break_minutes'))
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->nullable(),

            TextInput::make('overtime_minutes')
                ->label(__('settings.fields.overtime_minutes'))
                ->numeric()
                ->minValue(0)
                ->default(30)
                ->nullable(),

            TextInput::make('days_off_limit')
                ->label(__('settings.fields.days_off_limit'))
                ->numeric()
                ->minValue(0)
                ->default(5)
                ->nullable(),

            TextInput::make('encashment_limit')
                ->label(__('settings.fields.encashment_limit'))
                ->numeric()
                ->minValue(0)
                ->default(2)
                ->nullable(),

            Select::make('weekends')
                ->label(__('settings.fields.weekends'))
                ->multiple()
                ->options(__('settings.options.weekdays')),

            TextInput::make('default_payroll_start_day')
                ->label(__('settings.fields.default_payroll_start_day'))
                ->numeric(),

            Select::make('overtime_type')
                ->label(__('settings.fields.overtime_type'))
                ->options(__('settings.options.overtime_types'))
                ->default('Percentage'),

            TextInput::make('overtime_value')
                ->label(__('settings.fields.overtime_value'))
                ->numeric()
                ->minValue(0)
                ->default(1.5)
                ->nullable(),

            TextInput::make('default_work_from')
                ->label(__('settings.fields.default_work_from'))
                ->type('time')
                ->default('09:00')
                ->nullable(),

            TextInput::make('default_work_to')
                ->label(__('settings.fields.default_work_to'))
                ->type('time')
                ->default('17:00')
                ->nullable(),

            TextInput::make('grace_period_minutes')
                ->label(__('settings.fields.grace_period_minutes'))
                ->numeric()
                ->minValue(0)
                ->default(15)
                ->nullable(),

            Repeater::make('work_type_days')
                ->label(__('settings.fields.work_type_days'))
                ->schema([
                    Select::make('type')
                        ->label(__('settings.fields.contract_type'))
                        ->options(__('settings.options.contract_types'))
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
                        ->label(__('settings.fields.working_days'))
                        ->multiple()
                        ->options(__('settings.options.weekdays'))
                        ->required(),
                ])
                ->columns(2)
                ->maxItems(3),

            Section::make(__('settings.sections.work_hours.title'))
                ->schema([
                    Select::make('overtime_active_mode')
                        ->label(__('settings.fields.overtime_active_mode'))
                        ->options(__('settings.options.overtime_modes'))
                        ->reactive(),

                    TextInput::make('overtime_percentage')
                        ->label(__('settings.fields.overtime_percentage'))
                        ->numeric()
                        ->default(1.5),

                    TextInput::make('overtime_fixed_rate')
                        ->label(__('settings.fields.overtime_fixed_rate'))
                        ->numeric()
                        ->default(0)
                        ->prefix(__('settings.prefixes.currency')),
                ])->columns(3),

            Section::make(__('settings.sections.geofencing.title'))
                ->description(__('settings.sections.geofencing.description'))
                ->schema([
                    Map::make('location')
                        ->label(__('settings.fields.location'))
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
                                ->label(__('settings.fields.company_latitude'))
                                ->numeric()
                                ->readOnly(),

                            TextInput::make('company_longitude')
                                ->label(__('settings.fields.company_longitude'))
                                ->numeric()
                                ->readOnly(),

                            TextInput::make('radius_meter')
                                ->label(__('settings.fields.radius_meter'))
                                ->numeric()
                                ->default(100)
                                ->suffix(__('settings.suffixes.meters'))
                                ->live()
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
            ->title(__('settings.messages.saved'))
            ->send();
    }

    public function getRecord(): ?Setting
    {
        return Setting::query()->first();
    }
}
