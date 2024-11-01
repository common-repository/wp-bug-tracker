/**
 * Analyze View Object
 *
 * @var {ahmAdmin}
 */
function ahmAnalyze(parent){
    /**
     * Reference to ahmAdmin
     *
     * @var {ahmAdmin}
     */
    this.parent = parent;
    //lock the Control Panel
    parent.getView('control').lockControlPanel();
    //trigger the chain
    this.analyze();
}

/**
 * Analyze the report
 *
 * Trigger the AJAX Post request chain to analyze the list of error logs
 *
 * @access public
 */
ahmAnalyze.prototype.analyze = function(){
    var _this = this;
    var params = {
        'action' : 'ahm',
        'sub_action' : 'analyze',
        '_ajax_nonce': ahmLocal.nonce
    }
    jQuery.post(ajaxurl, params, function(data){
        if (data.stop){
            _this.parent.getView('control').unLockControlPanel();
            _this.parent.triggerView(ahmLocal.settings.view);
        }else{
            setTimeout(function(){_this.analyze();}, 200);
        }
    }, 'json');
}