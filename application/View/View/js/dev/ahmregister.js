/**
 * Registration View Object
 *
 * @var {ahmAdmin}
 */
function ahmRegister(parent){
    jQuery('#registration_more').bind('click', function(){
        jQuery('.register .hide').removeClass('hide');
    });
    parent.getView('control').lockControlPanel();

    var params = {
        'action' : 'ahm',
        'sub_action' : 'register',
        '_ajax_nonce': ahmLocal.nonce
    }
    jQuery.post(ajaxurl, params, function(data){
        if (data.status == 'success'){
            jQuery('.register .progress').addClass('progress-success').html('Success');
        }else{
            jQuery('.register .progress').addClass('progress-failed').html('Failed');
        }
        parent.getView('control').unLockControlPanel();
        jQuery('#reload').show();
    }, 'json');
}