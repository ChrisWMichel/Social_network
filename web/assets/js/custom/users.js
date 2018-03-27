$(document).ready(function () {
   let ias = jQuery.ias({
       container: '.box-users',
       item: '.user-item',
       pagination: '.pagination',
       next: '.pagination .next_link',
       triggerPageThreshold: 5
   });

   ias.extension(new IASTriggerExtension({
       text: 'Veiw more',
       offset: 3
   }));


    ias.extension(new IASSpinnerExtension({
        /*src: "{{ asset('assets/images/ajax-loader.gif') }}"*/
        src: image_path
    }));

   ias.extension(new IASNoneLeftExtension({
       text: 'There are no more people.'
   }));

   ias.on('ready',function (event) {
       followButtons();
   });

    ias.on('rendered',function (event) {
        followButtons();
    });
});

