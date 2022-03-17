<?php

namespace App\Console\Commands;

use App\Actions\CreateUser;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Admin';

    /**
     * Execute the console command.
     *
     * @param CreateUser $createUser
     * @return int
     */
    public function handle(CreateUser $createUser): int
    {
        $user = User::whereRole(Role::Admin)->count();
        if ($user > 0) {
            $this->info('Admin Exists');
            return 1;
        }

        $this->drawHeader();

        // get username
        $userName = $this->getInput(
            placeholder: 'Enter Admin Name',
            filed: 'name',
            rules: 'required|string'
        );

        // get email
        $email = $this->getInput(
            placeholder: 'Enter Email',
            filed: 'email',
            rules: 'required|email|unique:users'
        );

        // get password
        $password = $this->getInput(
            placeholder: 'Enter Password (type it\'s hidden)',
            filed: 'password',
            rules: 'required|string|min:8'
        );

        // create admin
        $admin = ($createUser(['name' => $userName, 'email' => $email, 'password' => $password, 'role' => Role::Admin]));

        // user didn't create
        if (!$admin) {
            $this->error('Sorry :( User Can\'t Created');
            return 1;
        }

        // user created
        $this->info('Admin Created Successfully :)');
        return 0;
    }

    /**
     * Draw Header in Terminal
     *
     * @return void
     */
    private function drawHeader(): void
    {
        $this->info('=========================');
        $this->info('=== Create New Admin ===');
        $this->info('=========================');
    }

    /**
     * Validate Input Via Laravel Validator and print the error
     *
     * @param array $data
     * @param array $rules
     * @return bool
     */
    private function validateInput(array $data, array $rules): bool
    {
        $validator = Validator::make(data: $data, rules: $rules);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return true;
        }
        return false;
    }

    private function getInput(string $placeholder, string $filed, array|string $rules): string
    {
        do {
            $value = $this->ask($placeholder);
        } while ($this->validateInput(
            data: [$filed => $value],
            rules: [$filed => $rules]
        ));

        return $value;
    }
}
