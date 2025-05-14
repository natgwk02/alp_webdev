<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['users_name' => 'Alice Smith', 'users_email' => 'alice@mail.com', 'users_phone' => '081234567001', 'users_address' => 'Jl. Mawar 1'],
            ['users_name' => 'Bob Johnson', 'users_email' => 'bob@mail.com', 'users_phone' => '081234567002', 'users_address' => 'Jl. Melati 2'],
            ['users_name' => 'Cindy Lee', 'users_email' => 'cindy@mail.com', 'users_phone' => '081234567003', 'users_address' => 'Jl. Kenanga 3'],
            ['users_name' => 'David Kim', 'users_email' => 'david@mail.com', 'users_phone' => '081234567004', 'users_address' => 'Jl. Anggrek 4'],
            ['users_name' => 'Eva Wong', 'users_email' => 'eva@mail.com', 'users_phone' => '081234567005', 'users_address' => 'Jl. Dahlia 5'],
            ['users_name' => 'Fikri Akbar', 'users_email' => 'fikri@mail.com', 'users_phone' => '081234567006', 'users_address' => 'Jl. Cemara 6'],
            ['users_name' => 'Gita Ayu', 'users_email' => 'gita@mail.com', 'users_phone' => '081234567007', 'users_address' => 'Jl. Flamboyan 7'],
            ['users_name' => 'Hendra Wijaya', 'users_email' => 'hendra@mail.com', 'users_phone' => '081234567008', 'users_address' => 'Jl. Sakura 8'],
            ['users_name' => 'Ika Lestari', 'users_email' => 'ika@mail.com', 'users_phone' => '081234567009', 'users_address' => 'Jl. Teratai 9'],
            ['users_name' => 'Joko Santoso', 'users_email' => 'joko@mail.com', 'users_phone' => '081234567010', 'users_address' => 'Jl. Bougenville 10'],
        ];

        foreach ($users as $user) {
            $firstName = strtolower(Str::before($user['users_name'], ' ')); // ambil nama depan
            DB::table('users')->updateOrInsert(
                ['users_email' => $user['users_email']],
                [
                    ...$user,
                    'users_password' => bcrypt($firstName), // password = nama depan
                    'status_del' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
    
    /**
     * Run the database seeds.
     */
   
        
        //     User::where('users_email', 'alice@mail.com')->update([
        //         'users_password' => Hash::make('alicesmith'),
        //     ]);

        //     User::where('users_email', 'bob@mail.com')->update([
        //         'users_password' => Hash::make('bobjohn'),
        //     ]);

        //     User::where('users_email', 'cindy@mail.com')->update([
        //         'users_password' => Hash::make('cindylee'),
        //     ]);

        //     User::where('users_email', 'david@mail.com')->update([
        //         'users_password' => Hash::make('davidkim'),
        //     ]);

        //     User::where('users_email', 'eva@mail.com')->update([
        //         'users_password' => Hash::make('evawong'),
        //     ]);

        //     User::where('users_email', 'fikri@mail.com')->update([
        //         'users_password' => Hash::make('fikriakbar'),
        //     ]);

        //     User::where('users_email', 'gita@mail.com')->update([
        //         'users_password' => Hash::make('gitayu'),
        //     ]);

        //     User::where('users_email', 'hendra@mail.com')->update([
        //         'users_password' => Hash::make('hendrawijaya'),
        //     ]);

        //     User::where('users_email', 'ika@mail.com')->update([
        //         'users_password' => Hash::make('ikalestari'),
        //     ]);

        //     User::where('users_email', 'joko@mail.com')->update([
        //         'users_password' => Hash::make('jokosantoso'),
        //     ]);
        
        // }
    