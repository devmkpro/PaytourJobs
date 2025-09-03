<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    /**
     * Lista dos modelos para os quais as permissões padrão serão geradas.
     */
    private array $models = ['User', 'Candidates'];

    /**
     * Definição das roles com:
     * - 'models': modelos para os quais gerar as permissões padrão. Use ['all'] para todos.
     * - 'custom_permissions': permissões adicionais necessárias.
     */
    private array $roles = [
        'admin' => [
            'models'            => ['all'], // Recebe todas as permissões dos modelos em $models
            'custom_permissions' => [
                'export_candidates',
                'download_resumes'
            ],
        ]

    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $this->createRolesAndPermissions();
        $this->removeObsoletePermissions();
    }

    /**
     * Cria roles e associa as permissões (dinâmicas e customizadas).
     */
    protected function createRolesAndPermissions(): void
    {
        $roleModel       = config('permission.models.role', Role::class);
        $permissionModel = config('permission.models.permission', Permission::class);

        foreach ($this->roles as $roleName => $data) {
            // Define os modelos: se for ['all'], pega todos os modelos da propriedade
            $models = ($data['models'] === ['all']) ? $this->models : $data['models'];

            // Gera permissões padrão para os modelos
            $dynamicPermissions = $this->generatePermissions($models);

            // Mescla com as permissões customizadas definidas para a role
            $allPermissions = array_merge($dynamicPermissions, $data['custom_permissions']);

            // Cria (ou pega) a role
            $role = $roleModel::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'web',
            ]);

            // Cria (ou pega) as permissões e associa à role
            $permissionModels = collect($allPermissions)->map(function ($permission) use ($permissionModel) {
                return $permissionModel::firstOrCreate([
                    'name'       => $permission,
                    'guard_name' => 'web',
                ]);
            })->all();

            $role->syncPermissions($permissionModels);
        }
    }

    /**
     * Gera as permissões padrão para os modelos informados.
     */
    private function generatePermissions(array $models): array
    {
        $actions = [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any'
        ];

        return collect($models)->flatMap(function ($model) use ($actions) {
            $modelName = strtolower($model);
            return collect($actions)->map(function ($action) use ($modelName) {
                return "{$action}_{$modelName}";
            });
        })->toArray();
    }

    /**
     * Remove permissões obsoletas dos usuários e roles.
     * Permissões obsoletas são aquelas que não estão mais definidas no array de roles.
     */
    protected function removeObsoletePermissions(): void
    {
        $roleModel = config('permission.models.role', Role::class);
        $permissionModel = config('permission.models.permission', Permission::class);
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        // Coleta todas as permissões que devem existir
        $validPermissions = collect();

        foreach ($this->roles as $roleName => $data) {
            $models = ($data['models'] === ['all']) ? $this->models : $data['models'];
            $dynamicPermissions = $this->generatePermissions($models);
            $allPermissions = array_merge($dynamicPermissions, $data['custom_permissions']);

            $validPermissions = $validPermissions->merge($allPermissions);
        }

        $validPermissions = $validPermissions->unique()->values();

        // Busca todas as permissões existentes no banco
        $existingPermissions = $permissionModel::where('guard_name', 'web')->get();

        // Identifica permissões obsoletas
        $obsoletePermissions = $existingPermissions->filter(function ($permission) use ($validPermissions) {
            return !$validPermissions->contains($permission->name);
        });

        if ($obsoletePermissions->isNotEmpty()) {
            $this->command->info('Removendo permissões obsoletas...');

            foreach ($obsoletePermissions as $obsoletePermission) {
                $this->command->warn("Removendo permissão obsoleta: {$obsoletePermission->name}");

                // Remove a permissão de todos os usuários
                $usersWithPermission = $userModel::permission($obsoletePermission->name)->get();
                foreach ($usersWithPermission as $user) {
                    $user->revokePermissionTo($obsoletePermission);
                    $this->command->line("  - Removida do usuário: {$user->name} (ID: {$user->id})");
                }

                // Remove a permissão de todas as roles
                $rolesWithPermission = $roleModel::permission($obsoletePermission->name)->get();
                foreach ($rolesWithPermission as $role) {
                    $role->revokePermissionTo($obsoletePermission);
                    $this->command->line("  - Removida da role: {$role->name}");
                }

                // Remove a permissão do banco de dados
                $obsoletePermission->delete();
            }

            $this->command->info("Foram removidas {$obsoletePermissions->count()} permissões obsoletas.");
        } 

        // Limpa permissões de usuários que não deveriam ter baseado em suas roles
        $this->cleanUserPermissions();
    }

    /**
     * Limpa permissões de usuários que não são compatíveis com suas roles.
     */
    protected function cleanUserPermissions(): void
    {
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');
        $users = $userModel::with(['roles', 'permissions'])->get();

        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                continue;
            }

            // Coleta todas as permissões que o usuário deveria ter baseado em suas roles
            $allowedPermissions = collect();

            foreach ($user->roles as $role) {
                if (isset($this->roles[$role->name])) {
                    $roleData = $this->roles[$role->name];
                    $models = ($roleData['models'] === ['all']) ? $this->models : $roleData['models'];
                    $dynamicPermissions = $this->generatePermissions($models);
                    $rolePermissions = array_merge($dynamicPermissions, $roleData['custom_permissions']);

                    $allowedPermissions = $allowedPermissions->merge($rolePermissions);
                }
            }

            $allowedPermissions = $allowedPermissions->unique();

            // Identifica permissões diretas do usuário que não são permitidas
            $userDirectPermissions = $user->permissions;
            $obsoleteUserPermissions = $userDirectPermissions->filter(function ($permission) use ($allowedPermissions) {
                return !$allowedPermissions->contains($permission->name);
            });

            if ($obsoleteUserPermissions->isNotEmpty()) {
                $this->command->info("Limpando permissões obsoletas do usuário: {$user->name} (ID: {$user->id})");

                foreach ($obsoleteUserPermissions as $obsoletePermission) {
                    $user->revokePermissionTo($obsoletePermission);
                    $this->command->line("  - Removida permissão: {$obsoletePermission->name}");
                }
            }
        }
    }
}
