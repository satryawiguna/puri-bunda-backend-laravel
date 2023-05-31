<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\UserLog\UserLogStoreRequest;
use App\Models\UserLog;
use App\Repositories\Contracts\IUserLogRepository;

class UserLogRepository extends BaseRepository implements IUserLogRepository
{
    public function __construct(UserLog $userLog)
    {
        parent::__construct($userLog);
    }

    public function createUserLog(UserLogStoreRequest $request): BaseEntity
    {
        $model = $this->_model->fill($request->all());

        $model->save();

        return $model->fresh();
    }
}