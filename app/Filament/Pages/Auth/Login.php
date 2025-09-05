<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string | Htmlable
    {
        return 'Acesso Administrativo';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Entre com suas credenciais para acessar o painel administrativo.';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(), 
                $this->getPasswordFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->placeholder('admin@admin.com')
            ->default('admin@admin.com')
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Senha')
            ->password()
            ->revealable(true)
            ->autocomplete('current-password')
            ->required()
            ->placeholder('password')
            ->default('password')
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
        ];
    }
}
