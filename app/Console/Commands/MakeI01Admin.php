<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeI01Admin extends Command
{
    protected $signature = 'i01:make-admin {email : Email tài khoản đã đăng ký}';
    protected $description = 'Cấp quyền quản trị I01 cho tài khoản đã đăng ký';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();
        if (!$user) {
            $this->error('Không tìm thấy tài khoản. Hãy đăng ký tài khoản trước.');
            return self::FAILURE;
        }
        $user->forceFill(['is_admin' => true])->save();
        $this->info('Đã cấp quyền quản trị cho '.$user->email);
        return self::SUCCESS;
    }
}
