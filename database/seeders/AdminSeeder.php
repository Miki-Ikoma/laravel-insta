<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->user->name = 'Adminstrator';
        $this->user->email = 'admin@gmail.com';
        $this->user->password = Hash::make('12345678');
        $this->user->role_id = User::ADMIN_ROLE_ID;
        $this->user->save();
    }
}
