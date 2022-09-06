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

        /*
            INSERT INTO `materials` (`id`, `nombre`, `extras`, `slug`, `created_at`, `updated_at`) VALUES
            (1, 'Plastico', 'swdefvr', 'plastico', '2022-07-30 01:31:46', '2022-07-30 01:31:46');

            INSERT INTO `techniques` (`id`, `nombre`, `slug`, `created_at`, `updated_at`) VALUES
            (1, 'Serigrafia', 'serigrafia', '2022-07-30 01:31:56', '2022-07-30 01:31:56'),
            (2, 'Tampo', 'tampo', '2022-07-30 01:54:11', '2022-07-30 01:54:11');

            INSERT INTO `material_technique` (`id`, `technique_id`, `material_id`) VALUES
            (2, 1, 1),
            (3, 2, 1);

            INSERT INTO `sizes` (`id`, `nombre`, `slug`, `created_at`, `updated_at`) VALUES
            (1, 'Pequeño', 'pequeno', '2022-07-30 01:32:09', '2022-07-30 01:32:09'),
            (2, 'Mediano', 'mediano', '2022-07-30 01:32:12', '2022-07-30 01:32:12');

            INSERT INTO `size_material_technique` (`id`, `size_id`, `material_technique_id`, `created_at`, `updated_at`) VALUES
            (1, 1, 2, NULL, NULL),
            (2, 2, 2, NULL, NULL),
            (3, 2, 3, NULL, NULL),
            (4, 1, 3, NULL, NULL);

            INSERT INTO `prices_techniques` (`id`, `size_material_technique_id`, `escala_inicial`, `escala_final`, `precio`, `tipo_precio`, `created_at`, `updated_at`) VALUES
            (1, 4, 1, 1000, '12.50', 'D', NULL, NULL);
        */
    }
}
