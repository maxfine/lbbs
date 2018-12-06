<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $users = factory(User::class)->times(10)->make()->each(function ($user, $index) use ($faker) {
            $user->avatar = $faker->imageUrl();
        });
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();
        User::insert($user_array);

        $user = User::find(1);
        $user->name = 'maxfine';
        $user->phone = '18576761970';
        $user->email = 'maxfine@163.com';
        $user->save();

        $user->assignRole('Founder');

        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
