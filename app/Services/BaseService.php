<?php

namespace App\Services;

use App\Core\Contracts\IService;
use App\Core\Entities\BaseEntity;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use Illuminate\Support\Collection;

class BaseService implements IService
{
    public function setMessageResponse(BasicResponse $response,
                                       string $type,
                                       int $codeStatus,
                                       string|array $message = null)
    {
        $response->type = $type;
        $response->codeStatus = $codeStatus;

        if (is_array($message)) {
            foreach ($message as $key => $value) {
                foreach ($value as $item) {
                    $method = "add" . ucfirst($type) . "MessageResponse";
                    $response->$method($item);
                }
            }
        } else {
            $method = "add" . ucfirst($type) . "MessageResponse";

            $response->$method($message);
        }

        return $response;
    }

    public function setGenericObjectResponse(GenericObjectResponse $response,
                                             BaseEntity|array|null $dto,
                                             string $type,
                                             int $codeStatus): GenericObjectResponse
    {
        $response->dto = $dto;
        $response->type = $type;
        $response->codeStatus = $codeStatus;

        return $response;
    }

    public function setGenericListResponse(GenericListResponse $response,
                                           Collection $dtoList,
                                           string $type,
                                           int $codeStatus): GenericListResponse
    {
        $response->dtoList = $dtoList;
        $response->type = $type;
        $response->codeStatus = $codeStatus;

        return $response;
    }

    public function setGenericListSearchResponse(GenericListSearchResponse $response,
                                                 Collection $dtoListSearch,
                                                 int $totalCount,
                                                 string $type,
                                                 int $codeStatus): GenericListSearchResponse
    {
        $response->dtoListSearch = $dtoListSearch;
        $response->totalCount = $totalCount;
        $response->type = $type;
        $response->codeStatus = $codeStatus;

        return $response;
    }

    public function setGenericListSearchPageResponse(GenericListSearchPageResponse $response,
                                                     Collection $dtoListSearchPage,
                                                     int $totalCount,
                                                     array $meta,
                                                     string $type,
                                                     int $codeStatus): GenericListSearchPageResponse
    {
        $response->dtoListSearchPage = $dtoListSearchPage;
        $response->totalCount = $totalCount;
        $response->meta = $meta;
        $response->type = $type;
        $response->codeStatus = $codeStatus;

        return $response;
    }
}
