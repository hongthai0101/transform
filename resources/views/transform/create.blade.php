@extends('adminlte::page')

@section('title', 'Add Transform | Dashboard')

@section('content_header')
    <h1>Add Transform</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-12">
                <form method="POST" action="{{ route('transforms.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Add New</h5>
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
                                <x-adminlte-input name="path" placeholder="Enter Path" label="Path" enableOldSupport="true">
                                    <x-slot name="bottomSlot">
                                    <span class="text-sm text-gray">
                                       <code>[{{ config('app.url') .'/api/' . '{providerPath}/{transformPath}' }}]</code>
                                    </span>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input name="name" placeholder="Enter Name" label="Name" enableOldSupport="true"/>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input name="description" placeholder="Enter Description" label="Description" enableOldSupport="true"/>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" id="json" type="radio" name="transform_type" value="json" checked>
                                    <label class="form-check-label" for="json">Response Transform Type Is JSON</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="xml" type="radio" name="transform_type" value="xml" >
                                    <label class="form-check-label" for="xml">Response Transform Type Is XML</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input name="to_url" placeholder="Enter To URL" label="To URL" enableOldSupport="true"/>
                            </div>
                            <x-adminlte-select2 name="to_method" label="To Method" enableOldSupport="true">
                                @foreach(config('transform.method') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </x-adminlte-select2>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" id="object" type="radio" name="to_response_data_type" value="object" checked>
                                    <label class="form-check-label" for="object">Response Transform Data Type Is Object</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="array" type="radio" name="to_response_data_type" value="array" >
                                    <label class="form-check-label" for="array">Response Transform Data Type Is Array</label>
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

@section('js')
    <script>

    </script>
@stop
@section('plugins.Select2', true)
