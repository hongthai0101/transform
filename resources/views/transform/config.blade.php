@extends('adminlte::page')

@section('title', 'Config Transform | Dashboard')
@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Transform Information</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item active">
                                <div class="nav-link">
                                    Provider:
                                    <span class="float-right">{{ $transform->provider->name }}</span>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="nav-link">
                                    Code:
                                    <span class="float-right">{{ $transform->code }}</span>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="nav-link">
                                    Name:
                                    <span class="float-right">{{ $transform->name }}</span>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="nav-link">
                                    Description:
                                    <span class="float-right">{{ $transform->description }}</span>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="nav-link">
                                    Transform Type:
                                    <span class="float-right">{{ $transform->transform_type }}</span>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="nav-link">
                                To URL:
                                    <span class="float-right">{{ $transform->to_url }}</span>
                                </div>
                            </li>
                            <li class="nav-item active">
                                <div class="nav-link">
                                    To Method:
                                    <span class="float-right">{{ $transform->to_method }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                    @livewire('transform-config', ['id' => $transform->id, 'type' => $type])
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .form-group {
            margin-bottom: 0.3rem;
        }
    </style>
@stop
