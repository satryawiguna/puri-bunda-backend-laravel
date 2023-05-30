<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\User\RegisterRequest;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\Contracts\IUserRepository;

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
            "full_name" => $request->full_name,
            "nick_name" => $request->nick_name,
        ]);

        $this->setAuditableInformationFromRequest($contact, $request);

        $user->contact()->save($contact);

        return $user->fresh();
    }

}
