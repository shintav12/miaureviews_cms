<div id="gallery" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Galería</h4>
            </div>
            <div class="modal-body box box-success">
                
                <form id="frm-gallery" method="post" enctype="multipart/form-data">
                    <!--<div id="title" class="form-group">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Nombre</label>
                                <input id="image_name" required name="image_name" class="form-control">
                            </div>
                        </div>
                    </div>-->
                    <div id="img_upload" class="form-group">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Imagen (*.jpg)</label>
                                <input id="gallery_file" required name="gallery_file" type="file" class="input_file">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-primary pull-right" type="submit">Subir</button>
                        </div>
                        <div id="error_images" style="display: none;"  class="col-xs-12">
                            <h3 style="color: red;">No has seleccionado ninguna imagen</h3>
                        </div>
                    </div>
                </form>
                <div class="form-group">
                    <div class="row">
                        <label for="short_name" class="col-sm-4 control-label">Galería de Imágenes</label>
                    </div>
                </div>
                <!--
                <div class="form-group">
                    <div class="row">
                        <div class="">
                            <div class="col-xs-12">
                                <label class="control-label">
                                    Buscar:
                                </label>
                            </div>
                            <div class="col-xs-11">
                                <input class="form-control" id="search_box" />
                            </div>
                            <div class="col-xs-1">
                                <a class="btn btn-primary" id="btn_search"><i class="fa fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div id="imgs" class="form-group">
                    <div class="row">
                        <div id="GalleryImagesContainer" class="col-xs-12" style="height: 400px; overflow-x: hidden; overflow-y: scroll; text-align: center;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <a id="insert_images" class="btn btn-primary pull-right">Insertar Imagen</a>
            </div>
        </div>
    </div>
</div>