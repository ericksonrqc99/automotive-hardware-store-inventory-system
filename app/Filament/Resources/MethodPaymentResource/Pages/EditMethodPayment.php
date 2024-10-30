<?php

namespace App\Filament\Resources\MethodPaymentResource\Pages;

use App\Filament\Resources\MethodPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMethodPayment extends EditRecord
{
    protected static string $resource = MethodPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
