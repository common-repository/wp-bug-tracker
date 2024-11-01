/**
 * Main WP Bug Tracker GUI object
 */
function ahmAdmin(){
    /**
     * List of Screen object
     * 
     * @var {Array}
     * 
     * @access public
     */
    this.viewList = new Array();
    
        /**
     * UI labels
     *
     * @var {Object}
     *
     * @todo Move to localization
     */
    this.label = {
        'graph' : 'Graph Screen',
        'list' : 'List of Errors Screen',
        'about' : 'About the Project Screen',
        'message' : 'Send a Message Screen',
        'register' : 'Registration Screen',
        'report' : 'Reporting Process Screen',
        'analyze' : 'Report analyzing process'
    }

    //Start Construct
    //initialize Control Panel first
    this.loadView('control').init();
    
    //initialize Patch Control Panel
    this.loadView('patch').init();
}

/**
 * Check if specified view already loaded
 *
 * @var {String} view
 *
 * @return {Boolean}
 *
 * @access public
 */
ahmAdmin.prototype.hasView = function(view){
    return (typeof this.viewList[view] == 'undefined' ? false : true);
}

/**
 * Get specified view object
 *
 * @var {String} view
 *
 * @return {Object}
 *
 * @access public
 */
ahmAdmin.prototype.getView = function(view){
    if (this.hasView(view)){
        var result = this.viewList[view];
    }else{
        console.log('View ' + view + ' is not initialized');
        result = null;
    }

    return result;
}

/**
 * Load specified view script and instantiate the object
 *
 * @var {String} view
 *
 * @return {Object}
 *
 * @access public
 */
ahmAdmin.prototype.loadView = function(view){
    var _this = this;
    if (!this.hasView(view)){
        jQuery.ajax({
            url: ahmLocal.pluginJS + '/ahm' + view + '.js',
            async : false,
            dataType: 'script',
            success: function(){
                var className = 'ahm' + view.charAt(0).toUpperCase() + view.slice(1);
                _this.viewList[view] = new window[className](_this);
            },
            error: function(){
                alert('Unable to load script for ' + view);
            }
        });
    }

    return this.getView(view);
}

/**
 * Trigger specified view
 *
 * @var {String} view
 *
 * @return void
 *
 * @access public
 */
ahmAdmin.prototype.triggerView = function(view){
    //make sure that view is specified, otherwise load default
    view = (view ? view : 'graph');
    //hide current view
    jQuery('#view_list > div').hide();
    //clear selected menu
    jQuery('#current_view > div').removeClass('ahm-menu-active');
    //load view if not loaded yet
    this.loadView(view);
    //show selected view
    jQuery('#view_list .' + view).show();
    //update current view icon and title
    jQuery('#current_view div[view="' + view + '"]').addClass('ahm-menu-active');
    jQuery('#metabox-main .hndle span').html(this.label[view]);
}

/**
 * Save GUI option
 *
 * @var {String}   key
 * @var {String}   value
 * @var {Callback} callback
 *
 * @return void
 *
 * @access public
 */
ahmAdmin.prototype.saveOption = function(key, value, callback){
    var params = {
        'action' : 'ahm',
        'sub_action' : 'save_option',
        '_ajax_nonce': ahmLocal.nonce,
        'key' : key,
        'value' : value
    }

    jQuery.post(ajaxurl, params, function(data){
        if ((data.status == 'success') && (typeof callback != 'undefined')){
            callback.call(this);
        }
    }, 'json');
}

/**
 * Add tooltip to selected DOM element
 * 
 * @param {object} element DOM element
 * 
 * @return void
 * 
 * @access public
 */
ahmAdmin.prototype.addTooltip = function(element){
    // Tooltip only Text
    jQuery(element).hover(function(){
        // Hover over code
        var title = jQuery(this).attr('title');
        jQuery(this).data('tipText', title).removeAttr('title');
        jQuery('<div class="ahm-tooltip"></div>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
    }, function() {
        // Hover out code
        jQuery(this).attr('title', jQuery(this).data('tipText'));
        jQuery('.ahm-tooltip').remove();
    }).mousemove(function(e) {
        var mousex = e.pageX + 15; //Get X coordinates
        var mousey = e.pageY + 15; //Get Y coordinates
        jQuery('.ahm-tooltip').css({
            top: mousey, 
            left: mousex
        })
    });
}

//Let's go!
jQuery(document).ready(function(){
    var ahmUI = new ahmAdmin();
});