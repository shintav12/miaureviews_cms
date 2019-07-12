<div id="embed" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Embed</h4>
            </div>
            <form id="frm-embed" method="post" enctype="multipart/form-data">
            <div class="modal-body box box-success">
                
                
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>URL</label>
                                <input id="url" required name="url" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div  class="form-group" style="display: none">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Tipo</label>
                                <?php $options = array("FACEBOOK","TWITTER"); ?>
                                <select class="form-control" name="type_embed" id="type_embed">
                                    <?php foreach ($options as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value ?>"><?php echo ucfirst(strtolower($value)) ?></option>
                                        <?php
                                    } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <a type="submit" class="btn btn-primary pull-right">Insertar</a>
            </div>
            </form>
        </div>
    </div>
</div>