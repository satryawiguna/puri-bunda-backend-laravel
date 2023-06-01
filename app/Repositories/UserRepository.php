<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements IUserRepository
{
    private readonly Contact $_contact;

    public function __construct(User $user, Contact $contact)
    {
        parent::__construct($user);

        $this->_contact = $contact;
    }

    public function register(RegisterRequest $request): BaseEntity
    {
        $user = $this->_model->fill([
            "role_id" => 2,
            "username" => $request->username,
            "email" => $request->email,
        ]);

        $this->setAuditableInformationFromRequest($user, $request);

        $user->setAttribute('password', bcrypt($request->password));

        $user->save();

        $contact = new $this->_contact([
            "type" => "EMPLOYEE",
            "full_name" => $request->full_name,
            "nick_name" => $request->nick_name
        ]);

        $this->setAuditableInformationFromRequest($contact, $request);

        $user->contact()->save($contact);

        return $user->fresh();
    }

    public function topTenUserByLogin(DashboardRequest $request): Collection
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $model = $this->_model
            ->with('userLogs')
            ->get();

        if ($request->start_date && $request->end_date) {
            return $model->sortBy(function($q) use ($startDate, $endDate) {
                return $q->userLogs->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            })->slice(0, 10);
        }

        return $model->sortBy(function($q) {
            return $q->userLogs->count();
        })->slice(0, 10);
    }
}
