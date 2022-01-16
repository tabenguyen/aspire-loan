<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'customer']);

        $suppervisor = new User();
        $suppervisor->name = "suppervisor";
        $suppervisor->email = "suppervisor@aspire.examlple";
        $suppervisor->password = bcrypt("suppervisor");
        $suppervisor->save();
        $suppervisor->assignRole('employee');

        $creditOfficer = new User();
        $creditOfficer->name = "creditOfficer";
        $creditOfficer->email = "creditOfficer@aspire.examlple";
        $creditOfficer->password = bcrypt("creditOfficer");
        $creditOfficer->save();
        $creditOfficer->assignRole('employee');


        $loaner = new User();
        $loaner->name = "loaner";
        $loaner->email = "loaner@aspire.examlple";
        $loaner->password = bcrypt("loaner");
        $loaner->save();
        $loaner->assignRole('customer');
    }
}
