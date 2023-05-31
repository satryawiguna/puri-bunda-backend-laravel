<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Employee\EmployeeListSearchDataRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\Contracts\IContactRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContactRepository extends BaseRepository implements IContactRepository
{
    private readonly User $_user;

    public function __construct(Contact $contact, User $user)
    {
        parent::__construct($contact);

        $this->_user = $user;
    }

    public function allEmployees(string $orderBy = "id", string $sort = "asc"): Collection
    {
        return $this->_model
            ->where('type', 'EMPLOYEE')
            ->orderBy($orderBy, $sort)
            ->get();
    }

    public function allSearchEmployees(EmployeeListSearchDataRequest $request): Collection
    {
        $employee = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $employee = $employee->whereRaw("(nick_name LIKE ?, full_name LIKE ?)", $this->searchContactByKeyword($keyword));
        }

        if ($request->unit_id) {
            $unitId = $request->unit_id;

            $employee = $employee->whereHas("units", function($q) use ($unitId) {
               return  $q->where('units.id', $unitId);
            });
        }

        if ($request->position_id) {
            $positionId = $request->position_id;

            $employee = $employee->whereHas("positions", function($q) use ($positionId) {
                return  $q->where('positions.id', $positionId);
            });
        }

        if ($request->join_date_start && $request->join_date_end) {
            $employee = $employee->whereBetween("join_date", [$request->join_date_start, $request->join_date_end]);
        }

        return $employee->where('type', 'EMPLOYEE')
            ->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchPageEmployees(ListSearchPageDataRequest $request): LengthAwarePaginator
    {
        $employee = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $employee = $employee->whereRaw("(nick_name LIKE ?, full_name LIKE ?)", $this->searchContactByKeyword($keyword));
        }

        if ($request->unit_id) {
            $unitId = $request->unit_id;

            $employee = $employee->whereHas("units", function($q) use ($unitId) {
                return  $q->where('units.id', $unitId);
            });
        }

        if ($request->position_id) {
            $positionId = $request->position_id;

            $employee = $employee->whereHas("positions", function($q) use ($positionId) {
                return  $q->where('positions.id', $positionId);
            });
        }

        if ($request->join_date_start && $request->join_date_end) {
            $employee = $employee->whereBetween("join_date", [$request->join_date_start, $request->join_date_end]);
        }

        return $employee->orderBy($request->order_by, $request->sort)
            ->paginate($request->per_page, ['*'], 'page', $request->page);
    }

    public function createEmployee(EmployeeStoreRequest $request, int $unitId, array $positionIds): BaseEntity
    {
        $user = new $this->_user([
            "role_id" => 2,
            "username" => $request->username,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        $this->setAuditableInformationFromRequest($user, $request);

        $user->save($user);

        $model = $this->_model->fill([
            "type" => "EMPLOYEE",
            "contactable_type" => $user::class,
            "contactable_id" => $user->id,
            "unit_id" => $unitId,
            "nick_name" => $request->nick_name,
            "full_name" => $request->full_name,
            "join_date" => Carbon::createFromFormat('Y-m-d', $request->join_date)
        ]);

        $this->setAuditableInformationFromRequest($model, $request);

        $model->save();

        $model->positions()->attatch($positionIds);

        return $model->fresh();
    }

    public function updateEmployee(EmployeeUpdateRequest $request, int $unitId, array $positionIds): BaseEntity|null
    {
        $model = $this->_model->find($request->id);

        if (!$model) {
            return null;
        }

        $this->setAuditableInformationFromRequest($model, $request);

        $model->update([
            "unit_id" => $unitId,
            "nick_name" => $request->nick_name,
            "full_name" => $request->full_name,
            "join_date" => Carbon::createFromFormat('Y-m-d', $request->join_date)
        ]);

        $model->positions()->sync($positionIds);

        return $model->fresh();
    }

    private function searchContactByKeyword(string $keyword) {
        return [
            'nick_name' => "%" . $keyword . "%",
            'full_name' => "%" . $keyword . "%"
        ];
    }

}
