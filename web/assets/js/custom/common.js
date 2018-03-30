$(document).ready(function () {

    if($(".label-notification").text() === 0){
        $(".label-notification").addClass("hidden");
    }else{
        $(".label-notification").removeClass("hidden");
    }

    if($(".label-notification-msg").text() === 0){
        $(".label-notification-msg").addClass("hidden");
    }else{
        $(".label-notification-msg").removeClass("hidden");
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

                if((response.count) === 0){
                    $(".label-notification").addClass("hidden");
                }else{
                    $(".label-notification").removeClass("hidden");
                }

            }
        });

        $.ajax({
            url: not_read_messages,
            type: 'GET',
            success: function (response) {
                $(".label-notification-msg").html(response.count) ;

                if((response.count) === 0){
                    $(".label-notification-msg").addClass("hidden");
                }else{
                    $(".label-notification-msg").removeClass("hidden");
                }

            }
        });
    }


});