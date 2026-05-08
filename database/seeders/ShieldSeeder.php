<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_application::settings::application::setting","view_any_application::settings::application::setting","create_application::settings::application::setting","update_application::settings::application::setting","restore_application::settings::application::setting","restore_any_application::settings::application::setting","replicate_application::settings::application::setting","reorder_application::settings::application::setting","delete_application::settings::application::setting","delete_any_application::settings::application::setting","force_delete_application::settings::application::setting","force_delete_any_application::settings::application::setting","view_laporan::masyarakats::laporan::masyarakat","view_any_laporan::masyarakats::laporan::masyarakat","create_laporan::masyarakats::laporan::masyarakat","update_laporan::masyarakats::laporan::masyarakat","delete_laporan::masyarakats::laporan::masyarakat","delete_any_laporan::masyarakats::laporan::masyarakat","verifikasi_laporan::masyarakats::laporan::masyarakat","tindak_lanjut_laporan::masyarakats::laporan::masyarakat","selesai_laporan::masyarakats::laporan::masyarakat","batal_laporan::masyarakats::laporan::masyarakat","hapus_autoritas_laporan::masyarakats::laporan::masyarakat","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_users::user","view_any_users::user","create_users::user","update_users::user","restore_users::user","restore_any_users::user","replicate_users::user","reorder_users::user","delete_users::user","delete_any_users::user","force_delete_users::user","force_delete_any_users::user","view_whatsapp::templates::whatsapp::template","view_any_whatsapp::templates::whatsapp::template","create_whatsapp::templates::whatsapp::template","update_whatsapp::templates::whatsapp::template","restore_whatsapp::templates::whatsapp::template","restore_any_whatsapp::templates::whatsapp::template","replicate_whatsapp::templates::whatsapp::template","reorder_whatsapp::templates::whatsapp::template","delete_whatsapp::templates::whatsapp::template","delete_any_whatsapp::templates::whatsapp::template","force_delete_whatsapp::templates::whatsapp::template","force_delete_any_whatsapp::templates::whatsapp::template","widget_KlasifikasiOverview","widget_YearlyLaporanChart"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
