<?php

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
        // 清除表时先忽略外键约束
        $this->foreign(0);
        $this->call(UsersTableSeeder::class);
        $this->call(CronTasksTableSeeder::class);
        $this->call(LinesTableSeeder::class);
        $this->call(BusLinesTableSeeder::class);
        $this->call(ApiParamTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->foreign(1);
    }

    private function foreign($type = 0)
    {
        if (in_array($type, [0, 1])) {
            DB::statement("SET FOREIGN_KEY_CHECKS=$type");
        }
    }
}
