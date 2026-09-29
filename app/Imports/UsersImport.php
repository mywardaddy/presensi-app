<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'nim'      => $row['nim'],
            'name'     => $row['name'],
            'gender'   => $row['gender'],
            'email'    => $row['email'] ?? null,
            'address'  => $row['address'] ?? null,
            'position' => $row['position'],
            'prodi'    => $row['prodi'],
            'role_id'  => $row['role_id'] ?? 2,
            'password' => Hash::make('nim'),
        ]);
    }
}
