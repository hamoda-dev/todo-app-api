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
        $data['password'] = bcrypt($data['password']);

        DB::beginTransaction();
        try {
            $createStatus = User::create($data);
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
