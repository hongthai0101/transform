<?php

namespace App\Http\Livewire;

use App\Models\Log;
use App\Models\Transform;
use App\Services\TransformService;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Xml;

class TransformConfig extends Component
{
    public string $message = '';

    public array $list = [];

    public int $transformId;

    public Transform $transform;

    public array $metadata = [];

    public string $type = '';

    public string $inputs = '';

    public string $outputs = '';

    public string $messageInput = '';

    public bool $validInput = true;

    public function mount(int $id, string $type)
    {
        $this->transformId = $id;
        $this->type = $type;
        $item = Transform::find($id);
        $configs = [];
        if ($type === 'request') {
            $configs = $item->request_transform ?? [];
        }
        if ($type === 'response') {
            $configs = $item->response_transform ?? [];
        }
        $this->list = $configs;

        // get data test
        $log = Log::where('transform_id', $id)->where('type', $type)->orderBy('id', 'DESC')->first();
        $this->inputs = $log ? $log->inputs : '';
        $this->outputs = $log ? $log->outputs : '';
        $this->transform = $item;
        $this->metadata = $item->metadata;
    }

    public function add()
    {
        $uuid = Str::uuid()->toString();
        $this->list[$uuid] = [
            'position' => 'header',
            'data_type' => 'string',
            'from_key' => '',
            'to_key' => '',
            'child' => [],
            'key' => $uuid,
            'livewireKey' => $uuid
        ];
    }

    public function remove($index, $isChild = false)
    {
        if ($isChild) {
            $this->removeChild($index);
            return;
        }
        $this->list = collect($this->list)->reject(function ($item) use ($index) {
            return $item['key'] === $index;
        })->toArray();
    }

    public function removeChild($key)
    {
        $current = $this->list;
        $keys = $this->findNestedKeys($current, 'key', $key)[0];
        array_pop($keys);
        $this->deletedKeyNestedArray($current, $keys);
        $this->list = $current;
    }

    public function addChild($key)
    {
        $uuid = Str::uuid()->toString();
        $current = $this->list;
        $keys = $this->findNestedKeys($current, 'key', $key)[0];
        $keys[count($keys) - 1] = 'child';
        $this->changeValueInNestedArray($current, $keys, [
            'data_type' => 'string',
            'from_key' => '',
            'to_key' => '',
            'child' => [],
            'key' => $uuid,
            'livewireKey' => implode('.', $keys) . '.' . $uuid
        ], $uuid);
        $this->list = $current;
    }

    public function render(): View
    {
        return view('livewire.transform-config');
    }

    public function store()
    {
        if ($this->type === 'request') {
            Transform::find($this->transformId)->update([
                'request_transform' => $this->list
            ]);
        }
        if ($this->type === 'response') {
            Transform::find($this->transformId)->update([
                'response_transform' => $this->list
            ]);
        }

        $this->message = 'Successfully saved';
    }

    public function transform()
    {
        $transform = Transform::find($this->transformId);
        if (!$transform) {
            $this->messageInput = 'Transform not found';
            $this->outputs = '';
            return;
        }

        $inputs = [];
        if ($transform->transform_type === 'xml') {
            $isValid = Xml::is_valid($this->inputs);
            if (!$isValid) {
                $this->messageInput = 'Invalid XML';
                $this->outputs = '';
                $this->validInput = false;
                return;
            }
            $inputs = Xml::decode($this->inputs);
        }

        if ($transform->transform_type === 'json') {
            $isValid = json_decode($this->inputs);
            if (!$isValid) {
                $this->messageInput = 'Invalid JSON';
                $this->outputs = '';
                $this->validInput = false;
                return;
            }
            $inputs = json_decode($this->inputs, true);
        }

        $transformService = new TransformService();
        $outputs = $transformService->transform($this->list, $inputs);
        $this->outputs = json_encode($outputs, JSON_PRETTY_PRINT);

        Log::create([
            'transform_id' => $this->transformId,
            'type' => $this->type,
            'inputs' => $this->inputs,
            'outputs' => $this->outputs
        ]);

        $this->validInput = true;
        $this->messageInput = '';
    }

    public function saveMetadata()
    {
        Transform::find($this->transformId)->update([
            'metadata' => $this->metadata
        ]);
        $this->message = 'Successfully saved';
    }

    private function findNestedKeys($array, $key, $value, $path = []): array {
        $keys = [];

        foreach ($array as $currentKey => $item) {
            $currentPath = array_merge($path, [$currentKey]);

            if (is_array($item)) {
                $nestedKeys = $this->findNestedKeys($item, $key, $value, $currentPath);
                $keys = array_merge($keys, $nestedKeys);
            } elseif ($currentKey === $key && $item === $value) {
                $keys[] = $currentPath;
            }
        }
        return $keys;
    }

    private function changeValueInNestedArray(&$array, $keys, $newValue, $uuid) {
        $currentKey = array_shift($keys);

        if (count($keys) > 0) {
            if (!isset($array[$currentKey]) || !is_array($array[$currentKey])) {
                $array[$currentKey] = [];
            }
            $this->changeValueInNestedArray($array[$currentKey], $keys, $newValue, $uuid);
        } else {
            $array[$currentKey][$uuid] = $newValue;
        }
    }

    private function deletedKeyNestedArray(&$array, $keys) {
        $currentKey = array_shift($keys);

        if (count($keys) > 0) {
            if (!isset($array[$currentKey]) || !is_array($array[$currentKey])) {
                return;
            }
            $this->deletedKeyNestedArray($array[$currentKey], $keys);
        } else {
            unset($array[$currentKey]);
        }
    }
}
