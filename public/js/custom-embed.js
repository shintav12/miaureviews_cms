(function ( $ ) {
    
    $.fn.embed = function(config) {
       callback = config.callback;
       $("#embed").modal();
       $("#type_embed").val(config.type);
    };

    $.fn.triggerEvents = function(){
       $('#embed').on('hidden.bs.modal', function () {
            $("#url").val("");
        });
       
       $("#frm-embed").validate({
            rules: {
              url: "required"
            },
            messages: {
              url : "Campo requerido"
            },
            submitHandler: function (form) {
             $('#embed').modal('toggle');
              embed = "["+$("#type_embed").val()+"]"+$("#url").val()+"[/"+$("#type_embed").val()+"]";
              callback(embed);
            }
       })
    }
 
}( jQuery ));