<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Arr;

class TransformService
{
    /**
     * @param $definition
     * @param array $data
     * @param bool $isMergeData
     * @return array
     * @throws Exception
     */
    public function transform($definition,array $data, bool $isMergeData = true): array
    {
        try {
            $keys = array_keys($data);
            $transformedData = [];

            if ($isMergeData) {
                $fromKeys = Arr::pluck($definition, "from_key");
                foreach ($keys as $k) {
                    if (!in_array($k, $fromKeys)) {
                        $transformedData[$k] = Arr::get($data, $k);
                        unset($data[$k]);
                    }
                }
            }

            foreach ($definition as $defKey => $def) {
                $fromKey = Arr::get($def, "from_key");
                $toKey = Arr::get($def, "to_key");
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
                                                $transformedChildData[$toKeyChild][] = $this->transform($childSub, $c, $isMergeData);
                                            }
                                        }else {
                                            $transformedChildData[$toKeyChild] = $this->transform($childSub, $childItem, $isMergeData);
                                        }
                                    }
                                }else {
                                    if (!is_array($childItem) && !is_object($childItem)) {
                                        $toKeyChild = $toKeyChild ?? $key;
                                        $transformedChildData[$toKeyChild] = $childItem;
                                    } else {
                                        $transformedChildData[] = $this->transform($childDefinition, $childItem, $isMergeData);
                                    }
                                }
                            }
                            $transformedData[$toKey] = $transformedChildData;
                        }
                    }
                }
            }

            return $transformedData;
        } catch (Exception $e) {
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
