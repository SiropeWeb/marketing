/*
author http://codecanyon.net/user/creativeinteractivemedia
*/

(function($) {

    $(document).ready(function() {

        function convert(id){

            $("#msg").text("Generating flipbook post " + converting + " of " + numItems)

            $.ajax({

                type: "POST",
                url: 'admin-ajax.php?page=real3d_flipbook_admin',
                data: {
                    id: id,
                    security: window.r3d_nonce[0],
                    action: "r3d_generate_post"
                },

                success: function(data, textStatus, jqXHR) {

                    converting++;

                    if(flipbooks.length)
                        convert(flipbooks.shift())
                    else
                        completed()

                },

                error: function(XMLHttpRequest, textStatus, errorThrown) {

                    alert("Status: " + textStatus);
                    alert("Error: " + errorThrown);

                }
            })

        }

        function completed(){
            $("#msg").text("All flipbook posts generated.")

            location.href = "edit.php?post_type=r3d"
        }

        flipbooks = flipbooks[0]

        var numItems = flipbooks.length
        var converting = 1

        if(flipbooks.length)
            convert(flipbooks.shift())
         else
            completed()

    });
})(jQuery);