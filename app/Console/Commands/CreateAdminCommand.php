<?php

namespace App\Console\Commands;

use App\Actions\CreateUser;
use App\Enums\Role;
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
        $this->drawHeader();

        // get username
        do {
            $userName = $this->ask('Enter Admin Name');
        } while ($this->validateInput(
            data: ['name' => $userName],
            rules: ['name' => 'required|string']
        ));

        // get email
        do {
            $email = $this->ask('Enter Email');
        } while ($this->validateInput(
            data: ['email' => $email],
            rules: ['email' => 'required|email|unique:users']
        ));

        // get password
        do {
            $password = $this->secret('Enter Password (type it\'s hidden)');
        } while ($this->validateInput(
            data: ['password' => $password],
            rules: ['password' => 'required|string|min:8']
        ));

        // create admin
        // user didn't create
        if (!($createUser(['name' => $userName, 'email' => $email, 'password' => $password, 'role' => Role::Admin]))) {
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
}
