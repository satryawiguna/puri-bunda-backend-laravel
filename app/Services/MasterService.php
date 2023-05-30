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
use App\Repositories\Contracts\IPositionRepository;
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

    public function __construct(IPositionRepository $positionRepository)
    {
        $this->_positionRepository = $positionRepository;
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
}
