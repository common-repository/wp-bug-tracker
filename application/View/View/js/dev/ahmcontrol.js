/**
 * Control Panel View Object
 *
 * @var {ahmAdmin}
 */
function ahmControl(parent){
    /**
     * Reference to ahmAdmin
     *
     * @var {ahmAdmin}
     */
    this.parent = parent;
}

/**
 * Initialize the GUI elements
 *
 * @return void
 *
 * @access public
 */
ahmControl.prototype.init = function(){
    //reference to itself
    var _this = this;

    jQuery('#current_view > div').each(function(){
        //add Tooltip
        _this.parent.addTooltip(jQuery(this));
        //bind and event
        jQuery(this).bind('click', function(){
            _this.parent.triggerView(jQuery(this).attr('view'));
        });
    });

    //Init Register button
    jQuery('#register').bind('click', function(){
        _this.parent.triggerView('register');
    });

    jQuery('#delete').bind('click', function(){
        jQuery('#clear').show();
    });
    this.parent.addTooltip(jQuery('#delete'));
    this.parent.addTooltip(jQuery('.twitter'));
    this.parent.addTooltip(jQuery('.link'));

    jQuery('#clear_no').bind('click', function(event){
        event.preventDefault();
        jQuery('#clear').hide();
    });
    jQuery('#clear_yes').bind('click', function(event){
        event.preventDefault();
        var params = {
            'action' : 'ahm',
            'sub_action' : 'clean',
            '_ajax_nonce': ahmLocal.nonce
        }
        jQuery.post(ajaxurl, params, function(data){
            if (data.status == 'success'){
                location.reload();
            }else{
                _this.unLockControlPanel();
                jQuery('#clear').hide();
                consol.log('Unable to clear the error log!');
            }
        }, 'json');
    });
    jQuery('#reload_page').bind('click', function(event){
        event.preventDefault();
        location.reload();
    });

    //Trigger the proper view. If system is not registered then register it
    if (ahmLocal.settings.registered != 'undefined'
                                     && ahmLocal.settings.registered){
        this.parent.triggerView('analyze');
    }else{
        //show registration form only once. If consumer is not registered show
        //the Register button instead of Report
        this.parent.triggerView('register');
    }
}

/**
 * Lock the Control Panel
 *
 * @return void
 *
 * @access public
 */
ahmControl.prototype.lockControlPanel = function(){
    jQuery('#submitdiv').append(jQuery('<div/>', {
        'class' : 'disabler'
    }));
}

/**
 * Unlock the Control Panel
 *
 * @return void
 *
 * @access public
 */
ahmControl.prototype.unLockControlPanel = function(){
    jQuery('#submitdiv .disabler').remove();
}