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
        // 生成数据集合
        $users = factory(User::class)->times(10)->make();

        // 让隐藏字段可见，并将数据集合转换为数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($user_array);

        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'december183';
        $user->email = 'guanyue584@163.com';
        $user->save();

        // 初始化用户，将1号用户指派为『站长』
        $user->assignRole('Founder');

        // 将2号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
