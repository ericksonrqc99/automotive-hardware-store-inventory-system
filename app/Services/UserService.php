<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function createUser(array $data): ?User
    {
        try {
            return User::create($data);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }


    public function findUserByDocument($document)
    {
        try {
            return User::where('ndocument', $document)->first();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
