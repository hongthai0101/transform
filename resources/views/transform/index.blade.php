@extends('adminlte::page')

@section('title', 'Transforms | Dashboard')

@section('content_header')
    <h1>Transforms</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <a href="{{route('transforms.create')}}" class="btn btn-primary">Add New</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tblData" class="table table-bordered table-striped dataTable dtr-inline">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Provider</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>To Method</th>
                                    <th>Transform</th>
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
                ajax:"{{route('transforms.index')}}",
                columns:[
                    {data:'id', name:'id'},
                    {data:'provider', name:'provider'},
                    {data:'code', name:'code'},
                    {data:'name', name:'name'},
                    {data:'description', name:'description'},
                    {data:'to_method', name:'to_method'},
                    {data:'transform', name:'transform', bSortable:false, className:"text-center"},
                    {data:'action', name:'action', bSortable:false, className:"text-center"},
                ],
                order:[[0, "desc"]]
            });
            $('body').on('click', '#btnDel', function(){
                var id = $(this).data('id');
                if(confirm('Delete Data '+id+'?')==true)
                {
                    var route = "{{route('transforms.destroy', ':id')}}";
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
