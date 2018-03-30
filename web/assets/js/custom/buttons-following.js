function followButtons() {
    $(".btn-follow").unbind("click").click(function(){
        $(this).addClass("hidden");
        $(this).parent().find(".btn-unfollow").removeClass('hidden');

        $.ajax({
            url:    follow,
            type: 'POST',
            data: {followed: $(this).attr("data-followed")},
            success: function (response) {
                console.log(response);
            }

        });
    });

    $(".btn-unfollow").unbind("click").click(function(){
        $(this).addClass("hidden");
        $(this).parent().find(".btn-follow").removeClass('hidden');

        $.ajax({
            url: unfollow,
            type: 'POST',
            data: {followed: $(this).attr("data-unfollowed")},
            success: function (response) {
                console.log(response);
            }

        });
    })
}