@extends('adminlte::page')

@section('title', 'Edit Transform | Dashboard')

@section('content_header')
    <h1>Edit Transform</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-12">
                <form method="POST" action="{{ route('transforms.update', $transform->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Edit Transform</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <x-adminlte-select2 name="provider_id" label="Provider" enableOldSupport="true">
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </x-adminlte-select2>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input
                                    name="path"
                                    placeholder="Enter Path"
                                    value="{{ $transform->path }}"
                                    label="Path"
                                    enableOldSupport="true"
                                >
                                    <x-slot name="bottomSlot">
                                    <span class="text-sm text-gray">
                                       <code>[{{ config('app.url') .'/api/' . '{providerPath}/{transformPath}' }}]</code>
                                    </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input
                                    name="name"
                                    placeholder="Enter Name"
                                    value="{{ $transform->name }}"
                                    label="Name"
                                    enableOldSupport="true"
                                />
                            </div>
                            <div class="form-group">
                                <x-adminlte-input
                                    name="description"
                                    placeholder="Enter Description"
                                    value="{{ $transform->description }}"
                                    label="Description"
                                    enableOldSupport="true"
                                />
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        id="json"
                                        type="radio"
                                        name="transform_type"
                                        value="json"
                                        @if($transform->transform_type == 'json') checked @endif
                                    />
                                    <label class="form-check-label" for="json">JSON</label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        id="xml"
                                        type="radio"
                                        name="transform_type"
                                        value="xml"
                                        @if($transform->transform_type == 'xml') checked @endif
                                    />
                                    <label class="form-check-label" for="xml">XML</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input
                                    name="to_url"
                                    placeholder="Enter To URL"
                                    value="{{ $transform->to_url }}"
                                    label="To URL"
                                    enableOldSupport="true"
                                />
                            </div>
                            <x-adminlte-select2 name="to_method" label="To Method" enableOldSupport="true">
                                @foreach(config('transform.method') as $key => $value)
                                    <option value="{{ $key }}" @if($key === $transform->to_method) selected @endif >{{ $value }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            <div class="form-group">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        id="object"
                                        type="radio"
                                        name="to_response_data_type"
                                        value="object"
                                        @if($transform->to_response_data_type == 'object') checked @endif
                                    >
                                    <label class="form-check-label" for="object">Object</label>
                                </div>
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        id="array"
                                        type="radio"
                                        name="to_response_data_type"
                                        value="array"
                                        @if($transform->to_response_data_type == 'array') checked @endif
                                    >
                                    <label class="form-check-label" for="array">Array</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
@section('plugins.Select2', true)
