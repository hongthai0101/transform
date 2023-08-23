<?php

namespace App\Http\Controllers\API;

use App\Services\TransformService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TransformController extends BaseController
{
    /**
     * @throws Exception
     */
    public function transform(Request $request)
    {
        $transformService = new TransformService();

        $transformItem = $request->transform;
        $inputs = $request->all();
        $toUrl = $transformItem->to_url;
        $toMethod = $transformItem->to_method;

        // Call api to get data
        $requestTransform = $transformItem->request_transform ?? [];
        $headersTransform = Arr::where($requestTransform, function ($value, $key) {
            return $value['position'] === 'header';
        });
        $queryTransform = Arr::where($requestTransform, function ($value, $key) {
            return $value['position'] === 'url';
        });
        $bodyTransform = Arr::where($requestTransform, function ($value, $key) {
            return $value['position'] === 'body';
        });

        $dataHeaderRequestTransform = $transformService->transform($headersTransform, $inputs);
        $dataQueryRequestTransform = $transformService->transform($queryTransform, $inputs);
        $dataBodyRequestTransform = $transformService->transform($bodyTransform, $inputs);

        $response = $this->executeRequest($toUrl, $toMethod, [
            'headers' => $transformService->removeEmptyValuesRecursive($dataHeaderRequestTransform),
            'query' => $dataQueryRequestTransform,
            'form_params' => $dataBodyRequestTransform,
        ], $transformItem->transform_type);

        $responseTransform = $transformItem->response_transform ?? [];
        $toResponseDataType = $transformItem->to_response_data_type;
        $responseToClient = [];
        if ($toResponseDataType === 'array') {
            foreach ($response as $value) {
                $responseToClient[] = $transformService->transform($responseTransform, $value);
            }
        } elseif ($toResponseDataType === 'object') {
            $responseToClient = $transformService->transform($responseTransform, $response);
        }

        return $transformService->removeEmptyValuesRecursive($responseToClient);
    }

    private function executeRequest($url, $method, $data, string $dataType = 'json')
    {
        $response = Http::send($method, $url, $data);
        return $dataType === 'json' ? $response->json() : xml_decode($response->body());
    }
}
