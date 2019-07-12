@extends('layout.master')

@section('css')
    <link href="{{asset("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{asset("assets/global/plugins/jquery-validation/js/jquery.validate.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/jquery-validation/js/additional-methods.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")}}" type="text/javascript"></script>
    <script class="text/javascript">
        $(document).ready(function(){

            $('.switch').bootstrapSwitch(
                {
                    'size': 'mini',
                    'AnotherName':'AnotherValue'
                }
            );

            $("#form-perms").validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.after(error);
                },
                rules: {
                    title: "required"
                },
                messages: {
                    title: "Campo requerido"
                },
                submitHandler: function (form) {
                    var values = [];
                    var checkboxs = $('.switch');
                    var ids = [];

                    for(var i = 0; i < checkboxs.length; i++){
                        values[i] = $('.switch').eq(i).is(':checked');
                        ids[i] = $('.object_id').eq(i).val();
                    }

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('perms_save') }}",
                        data: {
                            perms_check: values,
                            object_id: ids,
                            id: $("#id").val()
                        },
                        headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                        beforeSend: function () {
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
                            var error = data.error;
                            if (error == 0) {
                                window.location = "{{ url(route('auth_role'))}}";

                            } else {
                                swal.close();
                                swal(
                                    'Oops...',
                                    'Algo ocurrió!',
                                    'error'
                                );
                            }
                        }, error: function () {
                            swal.close();
                            swal(
                                'Oops...',
                                'Algo ocurrió!',
                                'error'
                            );
                        }
                    });
                }
            });

        });


    </script>
@endsection

@section('body')
    <h1 class="page-title"> {{$page_title}}
        <small>Permisos</small>
    </h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-body form">
                    <form id="form-perms" role="form" class="form-horizontal">
                        <div class="table-container col-xs-12" style="margin-top: 30px;">
                            <input  hidden id="id" name="id" value="{{$id}}"/>
                            {{csrf_field()}}
                            <table class="table table-striped table-bordered table-hover" id="menu" style="margin-top: 25px">
                                <thead style="background: grey; color: white" >
                                <tr>
                                    <th style="text-align: left;" width="10%"> # </th>
                                    <th style="text-align: left;" width="70%"> Módulos </th>
                                    <th style="text-align: left;" width="20$"> Permiso </th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $cont = 1?>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>
                                                <input hidden class="object_id" name="object_id[]" value="{{$item->id}}"/>
                                                {{$cont}}
                                            </td>
                                            <td>
                                                {{$item->name}}
                                            </td>
                                            <td>
                                                <input name="perms_check[]" data-on-text="&nbsp;ACTIVO&nbsp;" data-off-text="&nbsp;INACTIVO&nbsp;" class="switch" type="checkbox" <?php if(!is_null($item->object_id)) echo "checked"?> />
                                            </td>
                                        </tr>
                                        <?php $cont++;?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-md-3 col-md-9">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

