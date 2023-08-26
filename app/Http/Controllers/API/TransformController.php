<?php

namespace App\Http\Controllers\API;

use App\Models\Transform;
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
    public function transform(string $providerPath, string $transformPath, Request $request)
    {
        $transformService = new TransformService();

        $transformItem = Transform::where('path', $transformPath)->first();;
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

        $dataHeaderRequestTransform = $transformService->transform($headersTransform, $inputs, false);
        $dataQueryRequestTransform = $transformService->transform($queryTransform, $inputs, false);
        $dataBodyRequestTransform = $transformService->transform($bodyTransform, $inputs);

        $response = $this->executeRequest($toUrl, $toMethod, [
            'headers' => $transformService->removeEmptyValuesRecursive($dataHeaderRequestTransform),
            'query' => $dataQueryRequestTransform,
            'body' => json_encode($dataBodyRequestTransform),
        ], $transformItem->transform_type);

        if (empty($response)) {
            return [];
        }

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
        try {
            $response = Http::send($method, $url, $data);
            if ($response->status() !== 200 && $response->status() !== 201) {
                return [];
            }
            return $dataType === 'json' ? $response->json() : xml_decode($response->body());
        } catch (Exception $e) {
            return [];
        }
    }
}
