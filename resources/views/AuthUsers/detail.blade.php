@extends('layout.master')

@section('css')
<link href="{{asset("assets/global/plugins/file-input/css/fileinput.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/select2/css/select2.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/select2/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{asset("assets/global/plugins/jquery-validation/js/jquery.validate.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/jquery-validation/js/additional-methods.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/file-input/js/fileinput.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/select2/js/select2.js")}}" type="text/javascript"></script>
    <script class="text/javascript">
        $(document).ready(function(){
            $(".select2").select2();
            $("#input-25").fileinput({
                allowedFileExtensions: ["jpg"],
                uploadAsync: false,
                showUpload: false, // hide upload button
                showRemove: false,
                maxWidth : 154,
                maxHeight : 154,
                minWidth : 154,
                minHeight : 154,
                initialPreviewAsData: true,
                language: 'es',
                <?php if(isset($item) && !is_null($item->path)){?>
                    initialPreview: [
                        "<?php echo config('app.path_url').'auth_user/'. $item->id .'/'.$item->path.'?v='.strtotime($item->updated_at) ?>",
                    ]
                <?php }?>
            });
            $("#form-user").validate({
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
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('user_save') }}",
                        data: new FormData($("#form-user")[0]),
                        contentType: false,
                        processData: false,
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
                                window.location = "{{ url('auth_users')}}";
                            } else if(error == 2) {
                                swal.close();
                                swal(
                                    'Oops...',
                                    'Las Contraseñas son diferentes',
                                    'error'
                                );
                            }else{
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

            @if(!isset($item) || $item->role_id!=6)
                $('#box-author').css('display','block');
            @endif

            $("#role_id").on('change',function(){
                if($(this).val() != 6){
                    $('#box-author').css('display','block');
                }else{
                    $('#box-author').css('display','none');
                }
            })
        });


    </script>
@endsection

@section('body')
    <h1 class="page-title"> {{$page_title}}
        <small>{{$page_subtitle}}</small>
    </h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal" role="form" id="form-user">
                        {{csrf_field()}}
                        <input hidden name="id" value="<?php if( isset($item) )  echo $item->id; else echo 0;?>" />
                        <div class="row form-body">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <h4 class="" style="margin:0px;">
                                                Datos Personales
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Nombres</label>
                                            <input type="text" class="form-control" name="name" value="<?php if( isset($item) )  echo $item->first_name;?>" placeholder="Ingrese el nombre del nuevo Usuario">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Apellidos</label>
                                            <input type="text" class="form-control" name="last_name" value="<?php if( isset($item) )  echo $item->last_name;?>" placeholder="Ingrese el apellido del nuevo Usuario">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email" value="<?php if( isset($item) )  echo $item->email;?>" placeholder="Ingrese el apellido del nuevo Usuario">
                                        </div>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Rol</label>
                                            <select class="form-control select2" name="role_id" id="role_id">
                                                <?php foreach ($role as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value->id ?>" <?php echo (isset($item) && $item->role_id == $value->id) ? "selected" : "" ?> ><?php echo $value->name ?></option>
                                                    <?php
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <h4 class="" style="margin:0px;">
                                                Autenticación
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Usuario</label>
                                            <input type="text" class="form-control" name="user" value="<?php if( isset($item) )  echo $item->user;?>" placeholder="Ingrese el nombre del nuevo Usuario">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Contraseña</label>
                                            <input class="form-control" type="password" name="password" value="" placeholder="Ingrese la clave del usuario">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="col-xs-12">
                                            <label>Repetir Contraseña</label>
                                            <input class="form-control" type="password" name="password_other" value="" placeholder="Reingrese la clave del usuario">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-md-3 col-md-9">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a type="button" href="{{route("auth_users")}}" class="btn default">Cancel</a>
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

