<?php

namespace App\Filament\Resources\EventsResource\Pages;

use App\Filament\Resources\EventsResource;
use Filament\Resources\Pages\Page;

class Calendar extends Page
{
    protected static string $resource = EventsResource::class;

    protected static string $view = 'filament.resources.events-resource.pages.calendar';
}
