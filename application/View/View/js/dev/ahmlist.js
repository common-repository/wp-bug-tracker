/**
 * Error List View object
 *
 * @var {ahmAdmin} parent
 */
function ahmList(parent){
    /**
     * Reference to ahmAdmin
     *
     * @var {ahmAdmin}
     */
    this.parent = parent;

    /**
     * Reference to itself
     *
     * @var {ahmList}
     */
    var _this = this;

    /**
     * DataTable Object
     *
     * @var {Object}
     */
    this.list = null;

    /**
     * Custom filters to DataTabel
     *
     * @var {Object}
     */
    this._filters = {
        'type' : ahmLocal.settings.type,
        'module' : ahmLocal.settings.module,
        'patch' : ''
    }

    //add custom filtering
    jQuery.fn.dataTableExt.afnFiltering.push(function( oSettings, aData) {
        var response = true;
        //Error Type
        if (_this._filters.type && _this._filters.type != aData[3]){
            response = false;
        }
        //Error Module Name
        if (_this._filters.module && _this._filters.module != aData[4]){
            response = false;
        }
        //Error Solution
        if (_this._filters.patch && _this._filters.patch != aData[5]){
            response = false;
        }

        return response;
    });

    //init DataTable
    this.initList();
    
    //save current view
    parent.saveOption('view', 'list');
}

/**
 * Initialize the Error List Table
 * 
 * @return void
 * 
 * @access public
 */
ahmList.prototype.initList = function(){
    //reference to itself
    var _this = this;
    
    this.list = jQuery('#list').dataTable({
        sPaginationType: "full_numbers",
        sDom: "<'cpanel'lfr<'clear'>>t<'footer'ip<'clear'>>",
        bProcessing : true,
        bAutoWidth: false,
        bStateSave: true,
        sAjaxSource : ajaxurl,
        fnServerParams: function (aoData) {
            aoData.push({
                name : 'action',
                value : 'ahm'
            });
            aoData.push({
                name : 'sub_action',
                value : 'trigger_view'
            });
            aoData.push({
                name : '_ajax_nonce',
                value : ahmLocal.nonce
            });
            aoData.push({
                name : 'view',
                value : 'list'
            });
        },
        fnServerData: function ( sSource, aoData, fnCallback ) {
            //alert(fnCallback);
            jQuery.ajax( {
                dataType: 'json',
                type: "POST",
                url: sSource,
                data: aoData,
                success: function(data){
                    //add additional filters to oTable
                    _this.addFilters(data);
                    //populate oTable
                    fnCallback(data);
                }
            } );
        },
        aoColumnDefs: [
        {
            "bVisible": false, 
            "aTargets": [3, 4, 5]
        },

        {
            "sClass" : 'ahm-lo', 
            "aTargets" : [1]
        },
        {
            "sClass" : 'ahm-st', 
            "aTargets" : [2]
        }
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex ){ //format data
            var total = (aData[1][1] <= 100 ? aData[1][1] : '100+');
            jQuery('td:eq(1)', nRow).html(aData[1][0] + ' (' + total + ')');
        },
        fnInfoCallback: function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            var str = 'Show ' + iStart + ' to '+ iEnd + ' of ' + iTotal + ' errors';
            if (iMax !== iTotal){
                str += ' (filtered from total ' + iMax + ') ';
                str += '<a href="#" id="clear_filters">clear filters</a>';
            }
            
            return str;
        },
        fnDrawCallback: function( oSettings ) {
            jQuery('#list_wrapper #clear_filters').bind('click', function(event){
                event.preventDefault();
                _this.setFilter('type', '', false);
                _this.setFilter('module', '', false);
                _this.setFilter('patch', '', false);
                //clear the filters in db
                jQuery.post(ajaxurl, {
                    'action' : 'ahm',
                    'sub_action' : 'clear_filters',
                    '_ajax_nonce': ahmLocal.nonce
                });
                _this.list.fnDraw();
            });
        }
    });
}

/**
 * Refresh the Error List
 * 
 * @return void
 * 
 * @access public
 */
ahmList.prototype.refreshList = function(){
    //redraw the table again
    this.list.fnDestroy();
    this.initList();
}

/**
 * Add additional filter to dataTable
 *
 * @var {Object} data
 *
 * @return void
 *
 * @access public
 */
ahmList.prototype.addFilters = function(data){
    var _this = this;
    //add error type filter
    var type = '<label>Type<select id="filter_type">';
    type += '<option value="">All</option>';
    for(var errorType in data.aaStat.type){
        type += '<option value="' + errorType + '">' + errorType + ' (';
        type += data.aaStat.type[errorType] + ')</option>';
    }
    type += '</select></label>';
    jQuery('#list_length').after('<div class="dataTables_type"/>');
    jQuery('.dataTables_type').html(type);
    jQuery('#filter_type').bind('change', function(){
        _this.triggerFilter('type', jQuery(this).val(), true);
    });
    _this.triggerFilter('type', _this._filters.type, false);

    //add Module List
    var module = '<label>Module<select id="filter_module">';
    module += '<option value="">All</option>';
    for(var moduleName in data.aaStat.module){
        module += '<option value="' + moduleName + '">' + moduleName + ' (';
        module += data.aaStat.module[moduleName] + ')</option>';
    }
    module += '</select></label>';
    jQuery('.dataTables_type').after('<div class="dataTables_module"/>');
    jQuery('.dataTables_module').html(module);
    jQuery('#filter_module').bind('change', function(){
        _this.triggerFilter('module', jQuery(this).val(), true);
    });
    _this.triggerFilter('module', _this._filters.module, false);
}

/**
 * Set value for specific filter
 *
 * @var {String} filter
 * @var {String} value
 *
 * @return void
 *
 * @access public
 */
ahmList.prototype.setFilter = function(filter, value, save){
    this._filters[filter] = value;
    //check if filter is visual and adjust it
    if (jQuery('#filter_' + filter).length){
        //make sure that the option is available. This cover the case when
        //some option was selected before (and it was saved to database) and now
        //it is not actual
        if (jQuery('option[value="' + value + '"]', '#filter_' + filter).length){
            jQuery('#filter_' + filter).val(value);
        }else{
            this._filters[filter] = '';
        }
    }
    if (save){//save the filter
        this.parent.saveOption(filter, value);
    }
}

/**
 * Trigger filter
 *
 * @var {String} filter
 * @var {String} value
 * @var {Boolean} save
 *
 * @return void
 *
 * @access public
 */
ahmList.prototype.triggerFilter = function(filter, value, save){
    this.setFilter(filter, value, save);
    this.list.fnDraw();
}