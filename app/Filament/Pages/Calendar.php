<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Calendar extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.calendar';

    public function getTitle(): string | Htmlable
    {
        return __('Calendario');
    }

    public static function getNavigationLabel(): string
    {
        return __('Calendario');
    }
}
