<?php

namespace App\Services;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Core\Types\HttpResponseType;
use App\Http\Requests\Position\PositionStoreRequest;
use App\Http\Requests\Position\PositionUpdateRequest;
use App\Http\Requests\Unit\UnitStoreRequest;
use App\Http\Requests\Unit\UnitUpdateRequest;
use App\Repositories\Contracts\IPositionRepository;
use App\Repositories\Contracts\IUnitRepository;
use App\Services\Contracts\IMasterService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class MasterService extends BaseService implements IMasterService
{
    private readonly IPositionRepository $_positionRepository;

    private readonly IUnitRepository $_unitRepository;

    public function __construct(IPositionRepository $positionRepository,
                                IUnitRepository $unitRepository)
    {
        $this->_positionRepository = $positionRepository;
        $this->_unitRepository = $unitRepository;
    }

    public function getAllPositions(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $positions = $this->_positionRepository->all($request->order_by, $request->sort);

            $this->setGenericListResponse($response,
                $positions,
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

    public function getAllSearchPositions(ListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $positions = $this->_positionRepository->allSearchPositions($request);
            $positionsRowCount = $this->_positionRepository->count();

            $this->setGenericListSearchResponse($response,
                $positions,
                $positionsRowCount,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search position was succeed");
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

    public function getAllSearchPagePositions(ListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $positions = $this->_positionRepository->allSearchPagePositions($request);

            $this->setGenericListSearchPageResponse($response,
                $positions->getCollection(),
                $positions->total(),
                ["per_page" => $positions->perPage(), "current_page" => $positions->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search page position was succeed");
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

    public function getPosition(int $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $position = $this->_positionRepository->findById($id);

            if (!$position) {
                throw new ModelNotFoundException("Position by id: {' .  $id . '} was not found on " . __FUNCTION__ . "()");
            }

            $this->setGenericObjectResponse($response,
                $position,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch position was succeed");
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

    public function storePosition(PositionStoreRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();

            $createPosition = $this->_positionRepository->create($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $createPosition,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Create position was succeed");
        } catch (QueryException $ex) {
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

    public function updatePosition(int $id, PositionUpdateRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            if ($id != $request->id) {
                throw new BadRequestException('Path parameter id: {' . $id . '} was not match with the request');
            }

            $position = $this->_positionRepository->findById($id);

            if (!$position) {
                throw new ModelNotFoundException('Position by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $updatePosition = $this->_positionRepository->update($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $updatePosition,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Update position was succeed");
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

    public function destroyPosition(int $id): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $position = $this->_positionRepository->findById($id);

            if (!$position) {
                throw new ModelNotFoundException('Position by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $this->_positionRepository->delete($id);

            $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS,
                'Delete position by id: {' . $id . '} was succeed');

            Log::info('Delete position by id: {' . $id . '} was succeed');
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

    public function getAllUnits(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $units = $this->_unitRepository->all($request->order_by, $request->sort);

            $this->setGenericListResponse($response,
                $units,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all units was succeed");
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

    public function getAllSearchUnits(ListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $units = $this->_unitRepository->allSearchUnits($request);
            $unitsRowCount = $this->_unitRepository->count();

            $this->setGenericListSearchResponse($response,
                $units,
                $unitsRowCount,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search unit was succeed");
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

    public function getAllSearchPageUnits(ListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $units = $this->_unitRepository->allSearchPageUnits($request);

            $this->setGenericListSearchPageResponse($response,
                $units->getCollection(),
                $units->total(),
                ["per_page" => $units->perPage(), "current_page" => $units->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search page unit was succeed");
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

    public function getUnit(int $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $unit = $this->_unitRepository->findById($id);

            if (!$unit) {
                throw new ModelNotFoundException("Unit by id: {' .  $id . '} was not found on " . __FUNCTION__ . "()");
            }

            $this->setGenericObjectResponse($response,
                $unit,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch unit was succeed");
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

    public function storeUnit(UnitStoreRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();

            $createUnit = $this->_unitRepository->create($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $createUnit,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Create unit was succeed");
        } catch (QueryException $ex) {
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

    public function updateUnit(int $id, UnitUpdateRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            if ($id != $request->id) {
                throw new BadRequestException('Path parameter id: {' . $id . '} was not match with the request');
            }

            $unit = $this->_unitRepository->findById($id);

            if (!$unit) {
                throw new ModelNotFoundException('Unit by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $updateUnit = $this->_unitRepository->update($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $updateUnit,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Update unit was succeed");
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

    public function destroyUnit(int $id): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $unit = $this->_unitRepository->findById($id);

            if (!$unit) {
                throw new ModelNotFoundException('Unit by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $this->_unitRepository->delete($id);

            $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS,
                'Delete unit by id: {' . $id . '} was succeed');

            Log::info('Delete position by id: {' . $id . '} was succeed');
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
