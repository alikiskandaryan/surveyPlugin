const jq = jQuery.noConflict()
jq(document).ready(function ($){
    // survey answer ajax request for save in DB
        $(document).on('submit' , '#surveyquiz' , function (e){
            e.preventDefault();

            let formData = $(this).serializeArray();
//             console.log(formData)
            formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: window.location.origin + '/wp-admin/admin-ajax.php',
                data: formData,
                // async: false,
                //
                // enctype: 'multipart/form-data',


                success: function (res) {

                    console.log(res)



                },
                cache: false,
                contentType: false,
                processData: false,
            });
        })
})