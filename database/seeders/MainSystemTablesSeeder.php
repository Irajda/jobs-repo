<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MainSystemTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            $this->createPermission();

            $this->createRole();

            $this->createUser();

            DB::commit();
            Log::info("Successfully seeding permission, role and user");
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error("Error seeder {$e->getMessage()}");
        }

    }

    public function createPermission()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'job.view',
                'guard_name' => 'web'
            ],
            [
                'name' => 'job.create',
                'guard_name' => 'web'
            ],
            [
                'name' => 'job.assign',
                'guard_name' => 'web'
            ],
            [
                'name' => 'job.update',
                'guard_name' => 'web'
            ],
            [
                'name' => 'job.delete',
                'guard_name' => 'web'
            ],
        ]);
    }

    public function createRole()
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(['job.view', 'job.create', 'job.assign', 'job.update', 'job.delete']);

        $employeeRole = Role::create(['name' => 'employee', 'guard_name' => 'web']);
        $employeeRole->givePermissionTo(['job.view', 'job.assign', 'job.update']);
    }

    public function createUser()
    {
        $admin = User::create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'phone' => '+355691111111',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $employee1 = User::create([
            'name' => 'First Employee',
            'surname' => 'Employee',
            'phone' => '+355692222222',
            'email' => 'employee11@gmail.com',
            'time_zone' => 'America/Mexico_City',
            'password' => bcrypt('password'),
        ]);
        $employee1->assignRole('employee');

        $employee2 = User::create([
            'name' => 'Helen',
            'surname' => 'Troka',
            'phone' => '+355693333333',
            'email' => 'employee22@gmail.com',
            'time_zone' => 'Europe/Madrid',
            'password' => bcrypt('password'),
        ]);
        $employee2->assignRole('employee');
    }
}
