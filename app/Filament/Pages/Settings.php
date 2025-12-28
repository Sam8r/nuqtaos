<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
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
