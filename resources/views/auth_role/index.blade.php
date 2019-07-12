@extends('layout.master')


@section('css')
    <link href="{{asset("assets/global/plugins/datatables/datatables.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{asset("assets/global/scripts/datatable.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/datatables/datatables.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")}}" type="text/javascript"></script>
    <script type="text/javascript">
        var open = true;
        swal({
            title: 'Cargando...',
            text: '',
            showCancelButton: false,
            showConfirmButton: false,
            onOpen: function () {
                swal.showLoading()
            }
        });
        $(document).ready(function() {

            var dataTable = $('#user').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('get_role')}}",
                order: [[0, "asc"]],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar: ",
                    "sLoadingRecords": "Cargando...",
                    "processing":"Procesando...",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                },
                rowReorder: true,
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                ],
                "columnDefs": [
                    {
                        "searchable": false,
                        "targets": [ 0,2,3,4,5 ]
                    },{
                        "searchable": true,
                        "targets" : [1]
                    }
                ],
                "aoColumnDefs": [
                    {
                        "aTargets": [4],
                        "mData": null,
                        "mRender": function (data, type, full) {
                            if(full.status  === '1'){
                                return '<input type="checkbox" checked data-id="'+ full.id + '"  class="make-switch switch"  data-on-text="&nbsp;ACTIVO&nbsp;" data-off-text="&nbsp;INACTIVO&nbsp;" data-size="normal">';
                            }else{
                                return '<input type="checkbox"  data-id="'+ full.id + '" class="make-switch switch" data-on-text="&nbsp;ACTIVO&nbsp;" data-off-text="&nbsp;INACTIVO&nbsp;" data-size="normal">';
                            }
                        }

                    },
                    {
                        "aTargets": [5],
                        "mData": null,
                        "mRender": function (data, type, full) {
                            return '<a href="{{url("auth_role/detail")}}/' + full.id + '" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;Editar</a>'+

                            '<a href="{{url("auth_role/perms")}}/' + full.id + '" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp;Permisos</a>';
                        }

                    }
                ],
                "fnDrawCallback": function( oSettings ) {
                    swal.close();
                    open = true;
                    $('.switch').bootstrapSwitch(
                        {
                            'size': 'mini',
                            'onSwitchChange': function(event, state){
                                var id = $(this).data('id');
                                if($(this).is(':checked'))
                                    var status = 1;
                                else
                                    var status = 0;
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: "{{route('change_status_role')}}",
                                    headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                                    data: {
                                        id: id,
                                        status: status,
                                    },
                                    beforeSend: function (){
                                        open = false;
                                        swal({
                                            title: 'Cargando...',
                                            timer: 10000,
                                            showCancelButton: false,
                                            showConfirmButton: false,
                                            onOpen: function () {
                                                swal.showLoading()
                                            }
                                        });

                                    },
                                    success: function (data) {
                                        dataTable.ajax.reload();
                                    }
                                });
                            },
                            'AnotherName':'AnotherValue'
                        }
                    );

                }
            });
            $("#user").on( 'search.dt', function () {
                setTimeout(function(){
                        if(open) {
                            open = false;
                            swal({
                                title: 'Cargando...',
                                timer: 10000,
                                showCancelButton: false,
                                showConfirmButton: false,
                                onOpen: function () {
                                    swal.showLoading()
                                }
                            });
                        }
                    }
                    , 900);
            });

        });
    </script>
@endsection

@section('body')
    <h1 class="page-title"> {{$page_title}}
        <small>Listado</small>
    </h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <a href="{{route('role_user')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>&nbsp;Nuevo</a>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container" style="margin-top: 30px;">
                        {{csrf_field()}}
                        <table class="table table-striped table-bordered table-hover" id="user" style="margin-top: 25px">
                            <thead style="background: grey; color: white" >
                            <tr>
                                <th style="text-align: left;" width="5%"> # </th>
                                <th style="text-align: left;" width="20%"> Nombre </th>
                                <th style="text-align: left;" width="20%"> Fecha de Creación </th>
                                <th style="text-align: left;" width="20%"> Fecha de Actualización </th>
                                <th style="text-align: left;" width="15%"> Estado </th>
                                <th style="text-align: left;" width="20%"> Acciones </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection