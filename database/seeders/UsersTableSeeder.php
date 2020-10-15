<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Site;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Site::where('domain', config('app.base_domain'))->exists()) {
            $site = Site::where('domain', config('app.base_domain'))->firstOrFail();

            // Create admin user.
            if (!User::where('email', 'avargas@MDRepTime.com')->exists()) {
                $user = new User;
                $user->uuid = Str::uuid();
                $user->email = 'avargas@MDRepTime.com';
                $user->username = unique_username(Role::SUPER_ADMIN);
                $user->password = Hash::make('ax71bzld'); // Hash::make('xiuZ7Lo^p1vighii');
                $user->company  = 'MDRepTime, LLC';
                $user->first_name = 'Antonio';
                $user->last_name = 'Vargas';
                $user->address = '2233 Broderick Ave';
                $user->city = 'Duarte';
                $user->state = 'CA';
                $user->zipcode = '91010';
                $user->country = 'US';
                $user->mobile_phone = '16264197194';
                $user->status = User::ACTIVE;
                $user->terms = User::TERMS_ACCEPTED;
                $user->marketing = User::MARKETING_ACCEPTED;
                $user->setup_completed = User::SETUP_IGNORED;
                $user->email_verified_at = now();
                $user->save();

                // Assign role admin.
                $user->assignRole([Role::SUPER_ADMIN, Role::ADMIN]);

                // Assign user to site.
                $site->assignUser($user);
            }

            // Create admin user.
            if (!User::where('email', 'akirkwood@MDRepTime.com')->exists()) {
                $user = new User;
                $user->uuid = Str::uuid();
                $user->email = 'akirkwood@MDRepTime.com';
                $user->username = unique_username(Role::SUPER_ADMIN);
                $user->password = Hash::make('bsPXkpuc');
                $user->company  = 'MDRepTime, LLC';
                $user->first_name = 'Anthony';
                $user->last_name = 'Kirkwood';
                $user->address = '123 St.';
                $user->address_2 = '';
                $user->city = 'Austin';
                $user->state = 'TX';
                $user->zipcode = '73301';
                $user->country = 'US';
                $user->mobile_phone = '18584050597';
                $user->status = User::ACTIVE;
                $user->terms = User::TERMS_ACCEPTED;
                $user->marketing = User::MARKETING_ACCEPTED;
                $user->setup_completed = User::SETUP_IGNORED;
                $user->email_verified_at = now();
                $user->save();

                // Assign role admin.
                $user->assignRole([Role::SUPER_ADMIN, Role::ADMIN]);

                // Assign user to site.
                $site->assignUser($user);
            }

            if (!User::where('email', 'tlatino@MDRepTime.com')->exists()) {
                $user = new User;
                $user->uuid = Str::uuid();
                $user->email = 'tlatino@MDRepTime.com';
                $user->username = unique_username(Role::SUPER_ADMIN);
                $user->password = Hash::make('bsPXkpuc');
                $user->company  = 'MDRepTime, LLC';
                $user->first_name = 'Tony';
                $user->last_name = 'Latino';
                $user->address = '123 St.';
                $user->address_2 = '';
                $user->city = 'Austin';
                $user->state = 'TX';
                $user->zipcode = '73301';
                $user->country = 'US';
                $user->mobile_phone = '15128258921';
                $user->status = User::ACTIVE;
                $user->terms = User::TERMS_ACCEPTED;
                $user->marketing = User::MARKETING_ACCEPTED;
                $user->setup_completed = User::SETUP_IGNORED;
                $user->email_verified_at = now();
                $user->save();

                // Assign role admin.
                $user->assignRole([Role::SUPER_ADMIN, Role::ADMIN]);

                // Assign user to site.
                $site->assignUser($user);
            }
        }
    }
}
