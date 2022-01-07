<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:make-user {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据邮箱和密码生成一个管理员用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        if (User::query()->where('email', $email)->first()) {
            $this->error('创建失败，已有邮箱同名用户');
            return Command::FAILURE;
        }
        $user = new User();
        $user->name = 'administrator';
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_forbidden = 0;
        $user->save();
        $this->info('创建成功，您可以使用刚刚的用户登录了');
        return Command::SUCCESS;
    }
}
