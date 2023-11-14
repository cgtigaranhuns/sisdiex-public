<?php

namespace App\Filament\Pages\Auth;


use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\View;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;


class Login extends BaseAuth
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(), 
             //   $this->getUsernameFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    public function getHeading(): string|Htmlable
    {
        return 'SISDIEX - Inscrições';
    }

    

}
