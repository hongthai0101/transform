@extends('adminlte::page')

@section('title', 'Provider | Dashboard')

@section('content_header')
    <h1>Provider</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="errorBox"></div>
            <div class="col-3">
                <form method="POST" action="{{ $item ? route('providers.update', $item->id) : route('providers.store')}}">
                    @csrf
                    @if($item)
                        @method('PATCH')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h5>{{ $item ? 'Update Provider' : 'Add New' }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" placeholder="Enter Name" value="{{old('code', optional($item)->code)}}">
                                @if($errors->has('code'))
                                    <span class="text-danger">{{$errors->first('code')}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{old('name', optional($item)->name)}}">
                                @if($errors->has('name'))
                                    <span class="text-danger">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="description" placeholder="Enter Description" value="{{old('description', optional($item)->description)}}">
                                @if($errors->has('description'))
                                    <span class="text-danger">{{$errors->first('description')}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input name="is_active" class="custom-control-input" type="checkbox" id="is_active" @if(isset($item) && $item->is_active) checked @endif>
                                    <label for="is_active" class="custom-control-label">
                                        Active
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input name="is_authenticate" class="custom-control-input" type="checkbox" id="is_authenticate" @if(isset($item) && $item->is_authenticate) checked @endif>
                                    <label for="is_authenticate" class="custom-control-label">
                                        Authenticated
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h5>List</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--DataTable-->
                        <div class="table-responsive">
                            <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Authenticated</th>
                                    <th>Active</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function(){
            var table = $('#tblData').DataTable({
                reponsive:true, processing:true, serverSide:true, autoWidth:false,
                ajax:"{{route('providers.index')}}",
                columns:[
                    {data:'id', name:'id'},
                    {data:'code', name:'code'},
                    {data:'is_authenticate', name:'is_authenticate'},
                    {data:'is_active', name:'is_active'},
                    {data:'name', name:'name'},
                    {data:'description', name:'description'},
                    {data:'action', name:'action', bSortable:false, className:"text-center"},
                ],
                order:[[0, "desc"]]
            });
            $('body').on('click', '#btnDel', function(){
                //confirmation
                var id = $(this).data('id');
                if(confirm('Delete Data '+id+'?')==true)
                {
                    var route = "{{route('providers.destroy', ':id')}}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url:route,
                        type:"delete",
                        success:function(res){
                            $("#tblData").DataTable().ajax.reload();
                        },
                        error:function(res){
                            console.log(res);
                            $('#errorBox').html('<div class="alert alert-dander">'+response.message+'</div>');
                        }
                    });
                }else{
                    //do nothing
                }
            });
        });
    </script>
@stop

@section('plugins.Datatables', true)
