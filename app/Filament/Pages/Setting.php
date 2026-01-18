<?php

namespace App\Filament\Pages;

use App\Models\Setting as SettingModel;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class Setting extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Setting';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.setting';

    public ?array $data = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('儲存')
                ->action('save'),
        ];
    }

    public function mount(): void
    {
        $this->form->fill($this->getSetting());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('rmb_to_twd_rate')
                    ->label('人民幣兌換新台幣匯率')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function save()
    {
        $data = $this->form->getState();

        SettingModel::updateOrCreate(
            ['key' => 'rmb_to_twd_rate'],
            ['value' => $data['rmb_to_twd_rate']],
        );

        $recipient = Auth::user();

        $recipient->notify(
            Notification::make()
                ->title('Setting saved successfully')
                ->toDatabase(),
        );
    }

    private function getSetting(): array
    {
        $setting = SettingModel::pluck('value', 'key')->toArray();

        return [
            'rmb_to_twd_rate' => $setting['rmb_to_twd_rate'] ?? null,
        ];
    }
}
