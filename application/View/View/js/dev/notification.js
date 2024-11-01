jQuery(document).ready(function() {
    jQuery.ajax(ajaxurl, {
        type: 'POST',
        dataType: 'json',
        data: {
            'action': 'ahm',
            'sub_action': 'available_fixnum',
            '_ajax_nonce': ahmLocal.nonce
        },
        success: function(response) {
            if (response.number > 0) {
                setInterval(function(){
                    jQuery('#toplevel_page_ahm .wp-menu-image img').show().fadeOut(3000);
                }, 3200);
            }
        }
    });
});