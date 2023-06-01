<?php

namespace App\Services;

use App\Core\Requests\ListDataRequest;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Core\Types\HttpResponseType;
use App\Http\Requests\Employee\EmployeeListSearchDataRequest;
use App\Http\Requests\Employee\EmployeeListSearchPageDataRequest;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;
use App\Repositories\Contracts\IContactRepository;
use App\Repositories\Contracts\IPositionRepository;
use App\Repositories\Contracts\IUnitRepository;
use App\Services\Contracts\IEmployeeService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EmployeeService extends BaseService implements IEmployeeService
{
    private readonly IContactRepository $_contactRepository;
    private readonly IUnitRepository $_unitRepository;
    private readonly IPositionRepository $_positionRepository;

    public function __construct(IContactRepository $contactRepository,
                                IUnitRepository $unitRepository,
                                IPositionRepository $positionRepository) {
        $this->_contactRepository = $contactRepository;
        $this->_unitRepository = $unitRepository;
        $this->_positionRepository = $positionRepository;
    }

    public function getAllEmployees(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $employees = $this->_contactRepository->allEmployees($request->order_by, $request->sort);

            $this->setGenericListResponse($response,
                $employees,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all employees was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getAllSearchEmployees(EmployeeListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $employees = $this->_contactRepository->allSearchEmployees($request);
            $employeesRowCount = $this->_contactRepository->count();

            $this->setGenericListSearchResponse($response,
                $employees,
                $employeesRowCount,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search employees was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getAllSearchPageEmployees(EmployeeListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $employees = $this->_contactRepository->allSearchPageEmployees($request);

            $this->setGenericListSearchPageResponse($response,
                $employees->getCollection(),
                $employees->total(),
                ["per_page" => $employees->perPage(), "current_page" => $employees->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search page employees was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getAllCountEmployee(): GenericObjectResponse
    {
        $response = new GenericListSearchPageResponse();

        try {

        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getEmployee(string $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $employee = $this->_contactRepository->findById($id);

            if (!$employee) {
                throw new ModelNotFoundException("Employee by id: {' .  $id . '} was not found on " . __FUNCTION__ . "()");
            }

            $this->setGenericObjectResponse($response,
                $employee,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch employee was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (ModelNotFoundException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function storeEmployee(EmployeeStoreRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();

            $unit = $this->_unitRepository->findOrNew([
                "title" => $request->unit
            ]);

            $positions = new Collection();
            foreach ($request->positions as $key => $value) {
                $position = $this->_positionRepository->findOrNew([
                    "title" => $value
                ]);

                $positions->push($position->id);
            }

            $createEmployee = $this->_contactRepository->createEmployee($request, $unit->id, $positions->toArray());

            DB::commit();

            $this->setGenericObjectResponse($response,
                $createEmployee,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Create position was succeed");
        } catch (QueryException $ex) {
            DB::rollBack();
dd($ex->getMessage());
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (BadRequestException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Bad request');

            Log::error("Bad request on " . __FUNCTION__ . "()", [$ex->getMessage()]);

        } catch (Exception $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function updateEmployee(int $id, EmployeeUpdateRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            if ($id != $request->id) {
                throw new BadRequestException('Path parameter id: {' . $id . '} was not match with the request');
            }

            $employee = $this->_contactRepository->findById($id);

            if (!$employee) {
                throw new ModelNotFoundException('Employee by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $unit = $this->_unitRepository->findOrNew([
                "title" => $request->unit
            ]);

            $positions = new Collection();
            foreach ($request->positions as $key => $value) {
                $position = $this->_positionRepository->findOrNew([
                    "title" => $value
                ]);

                $positions->push($position->id);
            }

            $updateEmployee = $this->_contactRepository->updateEmployee($request, $unit->id, $positions->toArray());

            DB::commit();

            $this->setGenericObjectResponse($response,
                $updateEmployee,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Update employee was succeed");
        } catch(QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (ModelNotFoundException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (BadRequestException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Bad request');

            Log::error('Bad request on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (Exception $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function destroyEmployee(string $id): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $employee = $this->_contactRepository->findById($id);

            if (!$employee) {
                throw new ModelNotFoundException('Employee by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $this->_contactRepository->delete($id);

            $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS,
                'Delete employee by id: {' . $id . '} was succeed');

            Log::info('Delete employee by id: {' . $id . '} was succeed');
        } catch (ModelNotFoundException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch(QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (BadRequestException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Bad request');

            Log::error('Bad request on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (\Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid job destroy", $response->getMessageResponseError());
        }

        return $response;
    }

}
