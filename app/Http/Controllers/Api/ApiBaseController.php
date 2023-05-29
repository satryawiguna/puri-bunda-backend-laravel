<?php

namespace App\Http\Controllers\Api;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiBaseController extends Controller
{
    protected function getAllJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "messages" => $response->getMessageResponseAll()
        ], $response->getCodeStatus());
    }

    protected function getAllLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "message" => $response->getMessageResponseAllLatest()
        ], $response->getCodeStatus());
    }

    protected function getSuccessJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "messages" => $response->getMessageResponseSuccess()
        ], $response->getCodeStatus());
    }

    protected function getSuccessLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "message" => $response->getMessageResponseSuccessLatest()
        ], $response->getCodeStatus());
    }

    protected function getErrorJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "messages" => $response->getMessageResponseError()
        ], $response->getCodeStatus());
    }

    protected function getErrorLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "message" => $response->getMessageResponseErrorLatest()
        ], $response->getCodeStatus());
    }

    protected function getInfoJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "messages" => $response->getMessageResponseInfo()
        ], $response->getCodeStatus());
    }

    protected function getInfoLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "message" => $response->getMessageResponseInfoLatest()
        ], $response->getCodeStatus());
    }

    protected function getWarningJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "messages" => $response->getMessageResponseWarning()
        ], $response->getCodeStatus());
    }

    protected function getWarningLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "meta" => [
                "type" => $response->getType(),
                "code_status" => $response->getCodeStatus()
            ],
            "message" => $response->getMessageResponseWarningLatest()
        ], $response->getCodeStatus());
    }

    protected function getObjectJsonResponse(GenericObjectResponse $response,
                                             ?string $resource = null,
                                             ?array $meta = null): JsonResponse {

        $metaInit = [
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus()
        ];

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        return response()->json([
            "meta" => $meta,
            "data" => ($resource) ? new $resource($response->getDto()) : $response->getDto()
        ], $response->getCodeStatus());
    }

    protected function getListJsonResponse(GenericListResponse $response,
                                           ?string $resource = null,
                                           ?array $meta = null): JsonResponse {
        $metaInit = [
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus()
        ];

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        return response()->json([
            "meta" => $meta,
            "datas" => ($resource) ? new $resource($response->getDtoList()) : $response->getDtoList()
        ], $response->getCodeStatus());
    }

    protected function getListSearchJsonResponse(GenericListSearchResponse $response,
                                                 ?string $resource = null,
                                                 ?array $meta = null,): JsonResponse {
        $metaInit = [
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "total_count" => $response->getTotalCount()
        ];

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        return response()->json([
            "meta" => $meta,
            "datas" => ($resource) ? new $resource($response->getDtoListSearch()) : $response->getDtoListSearch()
        ], $response->getCodeStatus());
    }

    protected function getListSearchPageJsonResponse(GenericListSearchPageResponse $response,
                                                     ?string $resource = null,
                                                     ?array $meta = null,): JsonResponse {
        $metaInit = array_merge([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "total_count" => $response->getTotalCount()
        ], $response->getMeta());

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        return response()->json([
            "meta" => $meta,
            "datas" => ($resource) ? new $resource($response->getDtoListSearchPage()) : $response->getDtoListSearchPage()
        ], $response->getCodeStatus());
    }
}
