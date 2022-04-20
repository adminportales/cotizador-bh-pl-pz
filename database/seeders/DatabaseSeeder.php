<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'User Vendedor', // optional
            'description' => 'User is allowed to manage and edit other users', // optional
        ]);
        $seller = Role::create([
            'name' => 'seller',
            'display_name' => 'User Vendedor', // optional
            'description' => 'User is allowed to manage and edit other users', // optional
        ]);
        Company::create([
            'name' => 'BH TRADEMARKET',
            'image' => 'bhtrade.png'
        ]);

        Company::create([
            'name' => 'PROMO LIFE',
            'image' => 'promolife.png'
        ]);

        Company::create([
            'name' => 'PROMO ZALE',
            'image' => 'promodreams.png'
        ]);

        User::create([
            'name' => 'Antonio',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => 'efrmgtndfij',
            'company_id' => null,
        ])->attachRole($admin);

        User::create([
            'name' => 'Jaime',
            'email' => 'jaime@promolife.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'company_id' => 2,
        ])->attachRole($seller);
        User::create([
            'name' => 'Ricardo',
            'email' => 'ricardo@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => 'efrmgtndfij',
            'company_id' => 1,
        ])->attachRole($seller);
        User::create([
            'name' => 'Daniel',
            'email' => 'daniel@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => 'efrmgtndfij',
            'company_id' => 3,
        ])->attachRole($seller);
    }
}
