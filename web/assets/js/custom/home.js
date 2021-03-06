$(document).ready(function () {
    let ias = jQuery.ias({
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

    /*ias.extension(new IASSpinnerExtension({
        src: URL+'../assets/images/ajax-loader.gif'
    }));*/
    ias.extension(new IASSpinnerExtension({
        /*src: "{{ asset('assets/images/ajax-loader.gif') }}"*/
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
    });

    function Buttons() {
        $(".btn-img").unbind("click").click(function () {
            $(this).parent().find('.pub-image').fadeToggle();
        });

        $(".btn-delete-pub").unbind("click").click(function () {
            $(this).parent().parent().addClass('hidden');

            $.ajax({
                url: remove_publication,
                type: 'POST',
                data: {id: $(this).attr("data-id")},
                success: function (response) {
                    console.log(response.status);
                }
            });
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('.btn-like').unbind("click").click(function () {
            $(this).addClass("hidden");
            $(this).parent().find('.btn-unlike').removeClass("hidden");

            $.ajax({
                url: like_post,
                type: 'POST',
                data: {id:  $(this).attr("data-id")},
                success: function (response) {
                    console.log(response.status);
                }
            });
        });

        $('.btn-unlike').unbind("click").click(function () {
            $(this).addClass("hidden");
            $(this).parent().find('.btn-like').removeClass("hidden");

            $.ajax({
                url: unlike_post,
                type: 'POST',
                data: {id:  $(this).attr("data-id")},
                success: function (response) {
                    console.log(response.status);
                }
            });
        });

        $('.btn-delete-notice').unbind("click").click(function () {
            //$(this).addClass("hidden");
            //$(this).parent().find('.btn-like').removeClass("hidden");
            const id = $(this).attr("notice-data");
            $('#notification-row' + id).addClass("hidden");

            $.ajax({
                url: notificationRemove,
                type: 'POST',
                data: {id:  $(this).attr("notice-data")},
                success: function (response) {
                    console.log(response.status);
                }
            });
        })
    }
});