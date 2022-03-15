<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateUser
{
    /**
     * Action To Create New User
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function __invoke(array $data): array
    {
        DB::beginTransaction();
        try {
            $createStatus = (new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => $data['role'],
            ]))->save();

            $feedback = array(
                'status' => $createStatus,
                'user' => User::latest()->first(),
            );
        } catch (Exception) {
            DB::rollBack();
            throw new Exception('Can\'t Create New User');
        }
        DB::commit();

        return $feedback;
    }
}
