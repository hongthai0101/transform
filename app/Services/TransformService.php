<?php

namespace App\Services;

use Illuminate\Support\Arr;

class TransformService
{

    public function transform($definition, $data, $isRevert = false): array
    {
        try {
            $transformedData = [];

            foreach ($definition as $defKey => $def) {
                $fromKey = !$isRevert ? Arr::get($def, "from_key") : Arr::get($def, "to_key");
                $toKey = !$isRevert ? Arr::get($def, "to_key") : Arr::get($def, "from_key");
                $dataItem = Arr::get($data, $fromKey,null);

                if ($fromKey && $toKey) {
                    if ($dataItem !== null) {
                        $transformedData[$toKey] = $dataItem;
                    }

                    if (isset($def["child"]) && count($def["child"]) > 0) {
                        $childDefinition = $def["child"];
                        $childData = Arr::get($data, $fromKey, []);
                        $transformedChildData = [];

                        if (is_array($childData) || is_object($childData)) {
                            foreach ($childData as $key => $childItem) {

                                $childDefinitionItem = Arr::first($childDefinition, function ($item) use ($key) {
                                    return Arr::get($item, "from_key") === $key;
                                });
                                $datType = Arr::get($childDefinitionItem, "data_type");
                                $toKeyChild = Arr::get($childDefinitionItem, "to_key");
                                $fromKeyChild = Arr::get($childDefinitionItem, "from_key");

                                if ($fromKeyChild === $key && gettype($key) == 'string' && is_array($childItem)) {
                                    $childSub = Arr::get($childDefinitionItem, "child");
                                    $chiSubKey = Arr::get(array_values($childSub), '0.key');
                                    if ($chiSubKey) {
                                        $childSub[$chiSubKey]['child'] = $childSub;
                                        if ($datType === 'array') {
                                            foreach ($childItem as $c) {
                                                $transformedChildData[$toKeyChild][] = $this->transform($childSub, $c, $isRevert);
                                            }
                                        }else {
                                            $transformedChildData[$toKeyChild] = $this->transform($childSub, $childItem, $isRevert);
                                        }
                                    }
                                }else {
                                    if (!is_array($childItem) && !is_object($childItem)) {
                                        $transformedChildData[$toKeyChild] = $childItem;
                                    } else {
                                        $transformedChildData[] = $this->transform($childDefinition, $childItem, $isRevert);
                                    }
                                }
                            }
                            $transformedData[$toKey] = $transformedChildData;
                        }
                    }
                }
            }

            return $transformedData;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function removeEmptyValuesRecursive($array): array
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                // Nếu giá trị là mảng, thực hiện đệ quy
                return !empty($this->removeEmptyValuesRecursive($value));
            }
            // Loại bỏ các giá trị rỗng hoặc null
            return $value !== '' && !empty($value);
        });
    }
}
