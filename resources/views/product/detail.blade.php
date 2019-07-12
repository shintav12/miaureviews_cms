@extends('layout.master')

@section('css')
<link href="{{asset("assets/global/plugins/file-input/css/fileinput.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/bootstrap-summernote/summernote.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/select2/css/select2.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/select2/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
 <link href="{{asset("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css")}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .note-editable p {
            margin:0px 0px;
        }

    .overlay_images{
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 10;
        left: 0px;
        top: 0px;
        background: rgba(60, 141, 188, 0.7);
        display: none;
    }
    .overlay{
    	position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    z-index: 99999;
	    background: rgba(255, 255, 255, 0.7);
	    border-radius: 3px;
	    text-align: center;
    	line-height: 500px;
    }
    .modal-body{
        padding: 30px !important;
    }
</style>
@endsection

@section('scripts')
    <script src="{{asset("assets/global/plugins/dirty/jquery.dirrty.js")}}"></script>
    <script src="{{asset("assets/global/plugins/moment.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/jquery-validation/js/jquery.validate.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/jquery-validation/js/additional-methods.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/file-input/js/fileinput.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/bootstrap-summernote/summernote.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/file-input/js/locales/es.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/select2/js/select2.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js")}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset("js/custom-embed.js")}}?v=<?php echo date("YmdHis") ?>"></script>
    <script type="text/javascript" src="{{ asset("js/gallery.js")}}?v=<?php echo date("YmdHis") ?>"></script>
    
    <script src="{{asset("assets/global/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js")}}" type="text/javascript"></script>
    <script class="text/javascript">
        $(document).ready(function(){
            $("#el").activeEvent();
            $("#el").triggerEvents();
            $("#gallery_file").fileinput({
                allowedFileExtensions: ["jpg"],
                uploadAsync: false,
                showUpload: false, // hide upload button
                showRemove: false,
                initialPreviewAsData: true,
                language: 'es',
            });

            $("#open_gallery").click(function(){
                $(this).gallery({
                    cdn: "<?php echo config('app.path_url') ?>",
                    process : "<?php echo route('upload_images') ?>",
                    search : "<?php echo route('search_images') ?>",
                    callback: getImage
                });
            });
            function getImage(url){
                var summernoteValue = $("#description").summernote('code');
                summernoteValue += url;
                $("#description").summernote('code',summernoteValue);
            }
            $('#description').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear','style']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['insert', ['link']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ["view", ["codeview"]],
                ],
                styleTags: [ 'h3', 'h4', 'h5', 'h6'],
                oninit: function(){
                    var galeryBtn = '<button id="makeSnote" type="button" class="btn btn-default btn-sm btn-small" title="Add Code" data-event="something" tabindex="-1"><i class="fa fa-file-text"></i></button>'
                }
            });
            <?php if(isset($item)){
                $item->content = str_replace('"', "'", $item->content);
                ?>
                $('#description').summernote("code","<?php echo $item->content ?>");
                <?php
            } ?>


            $('.switch_input').bootstrapSwitch(
                {
                    'size': 'mini'
                }
            );
            $(".custom-embed").click(function(){
                var that = $(this);
                $(this).embed({
                    type: that.data("type"),
                    callback: function(data){
                        var summernoteValue = $("#description").summernote('code');
                        summernoteValue += data;
                        $("#description").summernote('code',summernoteValue);
                    }
                });
            })

            function getImage(url){
                var summernoteValue = $("#description").summernote('code');
                summernoteValue += url;
                $("#description").summernote('code',summernoteValue);
            }


            $("#input-25").fileinput({
                allowedFileExtensions: ["jpg"],
                uploadAsync: false,
                showUpload: false, // hide upload button
                showRemove: false,
                initialPreviewAsData: true,
                language: 'es',
                <?php if(isset($meta)){?>
                initialPreview: [
                   "<?php echo config('app.path_url').$meta->path.'?v='.strtotime($item->updated_at) ?>",
                ]
                <?php }?>
            });
            $("#input-24").fileinput({
                allowedFileExtensions: ["jpg"],
                uploadAsync: false,
                showUpload: false, // hide upload button
                showRemove: false,
                initialPreviewAsData: true,
                language: 'es',
                <?php if(isset($item)){?>
                initialPreview: [
                    "<?php echo config('app.path_url').$item->image.'?v='.strtotime($item->updated_at) ?>",
                ]
                <?php }?>
            });

            $('body').on('click','.delete',function(){
                $(this).parent().parent().remove();
            });
            <?php foreach($image_types as $image_type){ ?>
                $("#{{$image_type->name}}").fileinput({
                    allowedFileExtensions: ["jpg"],
                    uploadAsync: false,
                    showUpload: false, // hide upload button
                    showRemove: false,
                    initialPreviewAsData: true,
                    language: 'es',
                <?php if(isset($images)){
                    $image_aux = null;
                    foreach($images as $image) {
                        if ($image_type->id == $image->image_type) {
                            $image_aux = $image;
                            break;
                        }
                    }
                    if(!is_null($image_aux)){
                    ?>
                    initialPreview: [
                        "<?php echo config('app.path_url').$image->image.'?v='.strtotime($image->updated_at) ?>",
                    ]
                <?php }}?>
                });
            <?php }?>
            $("#tag_id").select2({
                minimumInputLength: 2,
                placeholder: "Busque un tag",
                ajax: {
                    url: "{{ url('products/search_tag')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            title: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results:  $.map(data.data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; },
                minimumInputLength: 1
            });
            $("#form-user").validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.after(error);
                },
                rules: {
                    name: "required",
                    subtitle: "required",
                    input_textarea: "required",
                    schedule: "required"
                },
                messages: {
                    name: "Campo requerido",
                    subtitle: "Campo requerido",
                    schedule: "Campo requerido"
                },
                submitHandler: function (form) {
                	if($(".kv-fileinput-error").css("display") == "block"){
                        swal.close();
                        swal(
                            'Oops...',
                            'La imagen que intenta subir no cumple con la medida indicada o tiene un formato inválido',
                            'error'
                        );
                    }else{
                        $("#description").summernote('removeFormat');
                        var summernoteValue = cleanHTML($("#description").summernote('code'));
                        $("#input_textarea").val(summernoteValue);
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "{{ route('product_save') }}",
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
                                    window.location = "{{ url(route('products'))}}";
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

                }
            });
            $("#form-tags").validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.after(error);
                },
                rules: {
                    new_tags: "required"
                },
                messages: {
                    new_tags: "Campo requerido"
                },
                submitHandler: function (form) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "{{ route('save_tags') }}",
                            data: new FormData($("#form-tags")[0]),
                            contentType: false,
                            processData: false,
                            beforeSend: function () {
                                $('#tag_modal').modal('toggle');
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
                                    $("#new_tag").val('');
                                    swal(
                                        'Éxito!',
                                        'Se crearon los tags exitósamente',
                                        'success'
                                    );
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
            $("#form-user").dirrty();
        });
    </script>
@endsection

@section('body')
	<div  id= "overlay2" class="overlay" style="display:none;">
        <i style="font-size:80px" class="fa fa-refresh fa-spin"></i>
    </div>
    <h1 class="page-title"> {{$page_title}}
        <small>{{$page_subtitle}}</small>
    </h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal" role="form" id="form-user" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <ul class="nav nav-tabs">
                            <li id="tab_li_1" class="tab-trigger active">
                                <a id="tab_1" href="#tab_1_1" data-toggle="tab"> General </a>
                            </li>
                            <li id="tab_li_1" class="tab-trigger">
                                <a id="tab_4" href="#tab_1_4" data-toggle="tab"> Imagenes</a>
                            </li>
                            <li id="tab_li_1" class="tab-trigger">
                                <a id="tab_1" href="#tab_1_2" data-toggle="tab"> Metas Web</a>
                            </li>
                            <li id="tab_li_1" class="tab-trigger">
                                <a id="tab_1" href="#tab_1_3" data-toggle="tab"> Metas Redes</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1_1">
                                <input hidden name="id" value="<?php if( isset($item) )  echo $item->id; else echo 0;?>" />
                                <div class="row form-body">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Título</label>
                                                    <input type="text" class="form-control" name="title" maxlength="200" value="<?php if( isset($item) )  echo $item->title;?>" placeholder="Ingrese el nombre del programa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Subtitulo</label>
                                                    <input type="text" class="form-control" name="subtitle" maxlength="200" value="<?php if( isset($item) )  echo $item->subtitle;?>" placeholder="Ingrese el nombre del programa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Amazon URL</label>
                                                    <input type="text" class="form-control" name="link_amazon" maxlength="600" value="<?php if( isset($item) )  echo $item->link_amazon;?>" placeholder="Ingrese el nombre del programa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Precio</label>
                                                    <input type="text" class="form-control" name="price" maxlength="600" value="<?php if( isset($item) )  echo $item->price;?>" placeholder="Ingrese el nombre del programa">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group ">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Imagen (1200*630 jpg) </label>
                                                    <input id="input-24" class="input-fixed" name="file_post" type="file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <a href="#" id="open_gallery" class="btn btn-primary pull-right">Abrir Galería</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Descripción</label>
                                                    <div id="description" class="summernote"></div>
                                                    <input id="input_textarea" name="description" hidden/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 post-contnet">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Tags</label>
                                                    <a data-toggle="modal" href="#tag_modal" class="btn btn-xs btn-primary">+</a>
                                                    <select name="tag_id[]" class="form-control select2" id="tag_id" multiple>
                                                        <?php if(isset($item)){?>
                                                            @foreach($tags as $tag)
                                                            <option value="{{$tag->id}}" selected>{{$tag->name}}</option>
                                                            @endforeach
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_1_4">
                                <div class="form-body">

                                     <div class="col-xs-12">
                                        <div id="gallery_container">
                                            <?php foreach($image_types as $image_type){?>
                                                    <div class="form-group" style="padding-bottom:25px">
                                                        <div class="col-xs-12" style="padding-top:15px">
                                                            <label><?php if($image_type->id == 1) echo ("ESTA NO VA EN EL CARROUSEL")?> {{$image_type->name}} .jpg</label>
                                                            <input id="<?php echo $image_type->name?>" class="input-fixed" name="<?php echo $image_type->name?>" type="file">
                                                        </div>
                                                    </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_1_2">
                                <div class="row form-body">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Título</label>
                                                    <input type="text" class="form-control" name="meta_title" value="<?php if( isset($meta) )  echo $meta->meta_title;?>" placeholder="Ingrese título">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Descripción</label>
                                                    <input type="text" class="form-control" name="meta_description" value="<?php if( isset($meta) )  echo $meta->meta_description;?>" placeholder="Ingrese descripción">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Keywords</label>
                                                    <input type="text" class="form-control" name="keywords" value="<?php if( isset($meta) )  echo $meta->meta_keywords;?>" placeholder="Ingrese keywords">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Meta index</label>
                                                    <input type="checkbox" name="meta_index" <?php if(isset($meta) && $meta->meta_index == 1)echo "checked"; ?> class="make-switch switch_input"  data-on-text="&nbsp;SI&nbsp;" data-off-text="&nbsp;NO&nbsp;" data-size="normal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Meta Follow</label>
                                                    <input type="checkbox" name="meta_follow" <?php if(isset($meta) && $meta->meta_follow == 1)echo "checked"; ?> class="make-switch switch_input"  data-on-text="&nbsp;SI&nbsp;" data-off-text="&nbsp;NO&nbsp;" data-size="normal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_1_3">
                                <div class="row form-body">
                                     <div class="col-xs-12">
                                        <div class="form-group ">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Imagen Compartir (1200*630 jpg) </label>
                                                    <input id="input-25" class="input-fixed" name="file" type="file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Título Facebook</label>
                                                    <input type="text" class="form-control" name="fb_title" value="<?php if( isset($meta) )  echo $meta->fb_title;?>" placeholder="Ingrese título facebook">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Descripción Facebook</label>
                                                    <input type="text" class="form-control" name="fb_description" value="<?php if( isset($meta) )  echo $meta->fb_description;?>" placeholder="Ingrese descripción facebook">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Título Twitter</label>
                                                    <input type="text" class="form-control" name="tw_title" value="<?php if( isset($meta) )  echo $meta->tw_title;?>" placeholder="Ingrese título twitter">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Descripción Twitter</label>
                                                    <input type="text" class="form-control" name="tw_description" value="<?php if( isset($meta) )  echo $meta->tw_description;?>" placeholder="Ingrese descripción twitter">
                                                </div>
                                            </div>
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
                                        <a type="button" href="{{route('products')}}" class="btn default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="el" id="el">
    <div class="modal fade bs-modal-sm in" id="tag_modal" tabindex="-1" role="dialog" aria-hidden="true" style="padding-right: 17px;">
        <div class="modal-dialog modal-sm">
            <form class="form-horizontal" role="form" id="form-tags">
                {{csrf_field()}}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Crear Nuevos Tag</h4>
                    </div>
                    <div class="modal-body">
                        
                            <input class="form-control" id="new_tag" name="new_tag" placeholder="Ejm:LOL,Dota2,Hearthstone,....,...">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include("layout.includes.images")
    @include("layout.includes.embeds")
    
@endsection

