(function ( $ ) {
    selected = "";
    activated = false;
    $.fn.gallery = function(config) {
       cdn = config.cdn;
       process_url = config.process;
       search = config.search;
       callback = config.callback;
       $("#gallery").modal();
    };

    $.fn.activeEvent = function(){
       $('#gallery').on('hidden.bs.modal', function () {
            $(".file-error-message").hide();
            $("#image_name").val("");
            $("#gallery_file").val('');
            selected = "";
        });
       $("body").on("click",".images_selected",function(e){
            $(".overlay_images").css("display","none");
            $(this).find(".overlay_images").css("display","block");
            selected = $(this).data("path");
       });

       $('#gallery').on('show.bs.modal', function (e) {
        $("#GalleryImagesContainer").html("");
          $("#overlay2").show();
          $.ajax({
            url: search,
            dataType:"json",
            success:function(data){
                $("#overlay2").hide();
                var total = data.images.length;
                for(var i=0;i<total;i++){
                    content = "";
                    content += '<div class="col-xs-3 images_selected" data-path="'+data.images[i].path+'" >';
                    content += "<div class='overlay_images' data-path='"+data.images[i].path+"'></div>"
                    content+='<div class="col-xs-12"><img style="width:80%;height:auto;" src="'+data.images[i].path+'" /></div>';
                    content+='</div>';
                    $("#GalleryImagesContainer").append(content);
                }
            }
          })
        });

       $("#insert_images").click(function(){
        if(selected != ""){
            image_html = "<img style='width:100%;height:auto' src='"+selected+"' />";
            callback(image_html);
            $("#gallery").modal('hide');
        }
       })

       

       $("#frm-gallery").validate({
            rules: {
            },
            messages: {
            },
            submitHandler: function (form) {
              $.ajax({
              type: "POST",
                dataType: "json",
                url: process_url,
                headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                data: new FormData($("#frm-gallery")[0]),
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#overlay2").show();
                },
                success: function (data) {
                    $("#overlay2").hide();
                  var error = data.error;
                    $("#image_name").val("");
                    $("#gallery_file").val('');
                  if (error == 0) {
                      content = '<div class="col-xs-3 images_selected" data-path="'+data.path+'" >';
                      content += "<div class='overlay_images' data-path='"+data.path+"'></div>"
                      content +='<div class="col-xs-12"><img style="width:80%;height:auto;" src="'+data.path+'" /></div>';
                      content +='</div>';
                      $("#GalleryImagesContainer").prepend(content);
                  } else {
                      
                  }
              }
              });
            }
       })
    }
 
}( jQuery ));