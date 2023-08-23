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
                                <x-adminlte-input name="code" placeholder="Enter Code" label="Code" enableOldSupport="true"/>
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
                                    <label class="form-check-label" for="json">JSON</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" id="xml" type="radio" name="transform_type" value="xml" >
                                    <label class="form-check-label" for="xml">XML</label>
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
