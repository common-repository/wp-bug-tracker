/**
 * Report View Object
 *
 * @var {ahmAdmin} parent
 */
function ahmReport(parent){
    this.parent = parent;
    parent.getView('control').lockControlPanel();
    //init GUI element
    jQuery('#report_more').bind('click', function(){
        jQuery('.report .hide').removeClass('hide');
    });
    jQuery('#automate').bind('click', function(){
        parent.saveOption('automate', 1, function(){
            jQuery('#automate').val('Reporting automated successfully!').attr('disabled', 'disabled');
            jQuery('#report').val('Reporting ON').unbind('click').bind('click', function(){
                parent.getView('control').showComfirmReportingOff();
            });
        });
    });

    //trigger the chain
    this.report();
}

/**
 * Reporting chain
 *
 * @return void
 *
 * @access public
 */
ahmReport.prototype.report = function(){
    var _this = this;
    var params = {
        'action' : 'ahm',
        'sub_action' : 'report',
        '_ajax_nonce': ahmLocal.nonce
    }
    jQuery.post(ajaxurl, params, function(data){
        if (data.stop){
            jQuery('.report .progress').addClass('progress-success');
            _this.parent.getView('control').unLockControlPanel();
            jQuery('#reload').show();
        }else{
            setTimeout(function(){_this.report();}, 200);
        }
    }, 'json');
}