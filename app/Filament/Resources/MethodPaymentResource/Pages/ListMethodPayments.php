<?php

namespace App\Filament\Resources\MethodPaymentResource\Pages;

use App\Filament\Resources\MethodPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMethodPayments extends ListRecords
{
    protected static string $resource = MethodPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
