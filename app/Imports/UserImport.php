<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Handle possible header variations
        $name = $row['name'] ?? ($row['Name'] ?? null);
        $username = $row['username'] ?? ($row['Username'] ?? null);
        $email = $row['email'] ?? ($row['Email'] ?? null);
        $password = $row['password'] ?? ($row['Password'] ?? ($row['massword'] ?? ($row['Massword'] ?? null)));
        $role = $row['role'] ?? ($row['Role'] ?? 'student');

        // Validate required fields
        if (!$name || !$email || !$password) {
            throw new \Exception("Missing required fields: name, email, password. Found: name={$name}, email={$email}, password={$password}");
        }

        return new User([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'plain_password' => $password,
            'role' => $role,
        ]);
    }
}
