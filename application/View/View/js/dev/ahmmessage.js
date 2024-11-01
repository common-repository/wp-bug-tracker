/**
 * About View Object
 *
 * @var {ahmAdmin}
 */
function ahmMessage(parent){
    parent.saveOption('view', 'message');
    
    //init the UI
    this.init();
}

/**
 * Initialize the UI
 * 
 * @return {void}
 */
ahmMessage.prototype.init = function(){
    var _this = this;
    
    //init send message button
    jQuery('#send_message').bind('click', function(){
        if (_this.valid()){
            _this.lockForm();
            jQuery.ajax(ajaxurl,{
                dataType : 'json',
                type : 'POST',
                data : {
                    'action' : 'ahm',
                    'sub_action' : 'send_email',
                    '_ajax_nonce': ahmLocal.nonce,
                    'name' : jQuery('#name').val(),
                    'email' : jQuery('#email').val(),
                    'message' : jQuery('#message').val()
                },
                error: function(){
                    jQuery('.message-failure').show().fadeOut(5000);
                },
                success : function(response){
                    if (response.status == 'success'){
                        jQuery('.message-success').show().fadeOut(5000);
                        jQuery('#name, #email, #message').val('');
                    }else{
                        jQuery('.message-failure').show().fadeOut(5000);
                    }
                },
                complete : function(){
                    _this.unLockForm();
                }
            })
        } 
    });
}

/**
 * Check if Send a Message form is valid
 * 
 * @return {Boolean}
 */
ahmMessage.prototype.valid = function(){
    var message = jQuery('#message').val();
    
    return (jQuery.trim(message) ? true : false);
}

/**
 * Lock the Form
 *
 * @return void
 *
 * @access public
 */
ahmMessage.prototype.lockForm = function(){
    jQuery('.contact-form').append(jQuery('<div/>', {
        'class' : 'disabler'
    }));
}

/**
 * Unlock the Form
 *
 * @return void
 *
 * @access public
 */
ahmMessage.prototype.unLockForm = function(){
    jQuery('.contact-form .disabler').remove();
}