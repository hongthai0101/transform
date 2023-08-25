@extends('adminlte::page')

@section('title', 'Change Password | Dashboard')

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('auth.change-password') }}">
                    @csrf
                    @method('PATCH')
                    <div class="card">
                        @if(session('success'))
                            <x-adminlte-alert class="bg-teal text-uppercase" icon="fa fa-lg fa-thumbs-up" title="Done" dismissable>
                                {{session('success')}}
                            </x-adminlte-alert>
                       @endif
                        <div class="card-header">
                            <div class="card-title">
                                <h5>Change Password</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <x-adminlte-input name="password" placeholder="Enter Password" label="Password" enableOldSupport="true" type="password"/>
                            </div>
                            <div class="form-group">
                                <x-adminlte-input name="password_confirmation" placeholder="Enter Password Confirmation" label="Password Confirmation" enableOldSupport="true" type="password"/>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Change</button>
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
