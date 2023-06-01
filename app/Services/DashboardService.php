<?php

namespace App\Services;

use App\Core\Responses\GenericListResponse;
use App\Core\Responses\IntegerResponse;
use App\Core\Types\HttpResponseType;
use App\Http\Requests\Dashboard\DashboardRequest;
use App\Repositories\Contracts\IContactRepository;
use App\Repositories\Contracts\IPositionRepository;
use App\Repositories\Contracts\IUnitRepository;
use App\Repositories\Contracts\IUserLogRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IDashboardService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class DashboardService extends BaseService implements IDashboardService
{
    private readonly IContactRepository $_contactRepository;
    private readonly IUnitRepository $_unitRepository;
    private readonly IPositionRepository $_positionRepository;
    private readonly IUserRepository $_userRepository;
    private readonly IUserLogRepository $_userLogRepository;

    public function __construct(IContactRepository $contactRepository,
                                IUnitRepository $unitRepository,
                                IPositionRepository $positionRepository,
                                IUserRepository $userRepository,
                                IUserLogRepository $userLogRepository) {
        $this->_contactRepository = $contactRepository;
        $this->_unitRepository = $unitRepository;
        $this->_positionRepository = $positionRepository;
        $this->_userRepository = $userRepository;
        $this->_userLogRepository = $userLogRepository;
    }

    public function getTotalEmployee(DashboardRequest $request): IntegerResponse
    {
        $response = new IntegerResponse();

        try {
            $totalEmployee = $this->_contactRepository->allCountEmployee($request);

            $this->setIntegerResponse($response, $totalEmployee, 'SUCCESS',
                HttpResponseType::SUCCESS);

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

    public function getTotalLogin(DashboardRequest $request): IntegerResponse
    {
        $response = new IntegerResponse();

        try {
            $totalLogin = $this->_userLogRepository->allCountUserLog($request);

            $this->setIntegerResponse($response, $totalLogin, 'SUCCESS',
                HttpResponseType::SUCCESS);

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

    public function getTotalUnit(): IntegerResponse
    {
        $response = new IntegerResponse();

        try {
            $totalUnit = $this->_unitRepository->allCountUnit();

            $this->setIntegerResponse($response, $totalUnit, 'SUCCESS',
                HttpResponseType::SUCCESS);

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

    public function getTotalPosition(): IntegerResponse
    {
        $response = new IntegerResponse();

        try {
            $totalPosition = $this->_positionRepository->allCountPosition();

            $this->setIntegerResponse($response, $totalPosition, 'SUCCESS',
                HttpResponseType::SUCCESS);

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

    public function getTopTenUserLogin(DashboardRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $topTenUserByLogin = $this->_userRepository->topTenUserByLogin($request);

            $this->setGenericListResponse($response,
                $topTenUserByLogin,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all positions was succeed");
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
}
