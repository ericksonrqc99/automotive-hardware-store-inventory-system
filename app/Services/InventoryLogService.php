<?php

namespace App\Services;

use App\Models\InventoryLog;
use Illuminate\Support\Collection;

class InventoryLogService
{

    public function storeInventoryLog(array $data): InventoryLog
    {
        try {
            foreach ($data as $key => $value) {
                if ($value === null) {
                    throw new \Exception(__("El campo $key es requerido"));
                }
            }
            $inventoryLog = InventoryLog::create($data);
            if (!$inventoryLog) {
                throw new \Exception(__("Error al crear el log de inventario"));
            }
            return $inventoryLog;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function storeInventoryLogs(array $data): bool
    {
        try {
            $inventoryLogs = InventoryLog::insert($data);
            if (!$inventoryLogs) {
                throw new \Exception(__("Error al crear los logs de inventario"));
            }
            return $inventoryLogs;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
