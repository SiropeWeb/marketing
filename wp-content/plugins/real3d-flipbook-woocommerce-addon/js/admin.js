/*
author http://codecanyon.net/user/creativeinteractivemedia
*/

(function ($) {
    "use strict";
    $(document).ready(function () {
        
        $(".full-flipbook").find(".r3d-thumb").on("click", function(e){
            if($(this).hasClass("r3d-thumb-selected")){
                $(this).removeClass("r3d-thumb-selected")
                $("#r3d_preview_id").val("")
            }else{
                $(".full-flipbook").find(".r3d-thumb-selected").removeClass("r3d-thumb-selected")
                $(this).addClass("r3d-thumb-selected")
                $("#r3d_flipbook_id").val(this.dataset.id)
            }
        })

        $(".preview-flipbook").find(".r3d-thumb").on("click", function(e){
            if($(this).hasClass("r3d-thumb-selected")){
                $(this).removeClass("r3d-thumb-selected")
                $("#r3d_preview_flipbook_id").val("")
            }else{
                $(".preview-flipbook").find(".r3d-thumb-selected").removeClass("r3d-thumb-selected")
                $(this).addClass("r3d-thumb-selected")
                $("#r3d_preview_flipbook_id").val(this.dataset.id)
            }
            
        })
    })
})(jQuery);
       