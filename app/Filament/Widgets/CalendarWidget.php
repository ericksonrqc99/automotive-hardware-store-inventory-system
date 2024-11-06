<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Event::class;
    

    public function fetchEvents(array $fetchInfo): array
    {
        return Event::where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Event $event) {
                return [
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->starts_at,
                    'end' => $event->ends_at,
                ];
            })
            ->toArray();
    }
    public function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
            Grid::make()
                ->schema([
                    DateTimePicker::make('starts_at'),
                    DateTimePicker::make('ends_at'),
                ]),
        ];
    }
    public static function canView(): bool
    {
        return false;
    }
}
