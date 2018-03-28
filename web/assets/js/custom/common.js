$(document).ready(function () {
    /*let ias = jQuery.ias({
        container: '#timeline .box-content',
        item: '.publication-item',
        pagination: '#timeline .pagination',
        next: '#timeline .pagination .next_link',
        triggerPageThreshold: 5
    });

    ias.extension(new IASTriggerExtension({
        text: 'Veiw more',
        offset: 3
    }));

    /!*ias.extension(new IASSpinnerExtension({
        src: URL+'../assets/images/ajax-loader.gif'
    }));*!/
    ias.extension(new IASSpinnerExtension({
        /!*src: "{{ asset('assets/images/ajax-loader.gif') }}"*!/
        src: image_path
    }));

    ias.extension(new IASNoneLeftExtension({
        text: 'There are no more posts.'
    }));

    ias.on('ready',function (event) {
        Buttons();
    });

    ias.on('rendered',function (event) {
        Buttons();
    });*/

    if($(".label-notification").text() === 0){
        $(".label-notification").addClass("hidden");
    }else{
        $(".label-notification").removeClass("hidden");
    }

    notifications();

    setInterval(function () {
        notifications();
    }, 60000);

    function notifications() {
        $.ajax({
            url: notification,
            type: 'GET',
            success: function (response) {
                $(".label-notification").html(response.count) ;

                if($(response.count).text() === 0){
                    $(".label-notification").addClass("hidden");
                }else{
                    $(".label-notification").removeClass("hidden");
                }

            }
        });
    }
});