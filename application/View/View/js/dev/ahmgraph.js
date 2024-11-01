/**
 * Graph View Object
 *
 * @var {ahmAdmin}
 */
function ahmGraph(parent){
    /**
     * Reference to ahmAdmin
     *
     * @var {ahmAdmin}
     */
    this.parent = parent;

    /**
     * Reference to itself
     *
     * @var {ahmGraph}
     */
    var _this = this;

    /**
     * Internal cache
     *
     * @var {Object}
     */
    this.cache = {
        'line' : null,
        'pie' : null
    }

    //Init graph switch panel
    jQuery('.graph-line').bind('click', function(){
        _this.loadGraph('line');
    });
    jQuery('.graph-pie').bind('click', function(){
        _this.loadGraph('pie');
    });
    //Init tooltip for Graphs
    jQuery('.graph-container > div').bind('plothover', function (event, pos, item) {
        if (item) {
            jQuery('#tooltip').remove();
            switch(jQuery(this).attr('id')){
                case 'graph-lines':
                    var data = item.datapoint[1];
                    break;

                case 'graph-pie':
                    data = item.datapoint[0].toFixed(2) + '%';
                    break;

                default:
                    break;
            }
            _this.showTooltip(pos.pageX, pos.pageY, data);
        } else {
            jQuery('#tooltip').remove();
        }
    });
    //load first default graph
    _this.loadGraph('line');
    //save current view
    parent.saveOption('view', 'graph');
}

/**
 * Load the Graph
 *
 * @var {String} name
 *
 * @return void
 *
 * @access public
 */
ahmGraph.prototype.loadGraph = function(name){
    //reference to itself
    var _this = this;
    //lock the Control Panel
    this.parent.getView('control').lockControlPanel();

    //underline the proper graph
    jQuery('.graph-list a').removeClass('graph-type-active');
    jQuery('.graph-list .graph-' + name).addClass('graph-type-active');
    //show proper explanation
    jQuery('.graph-explain').addClass('hide');
    jQuery('#explain_' + name).removeClass('hide');
    //hide current graph
    jQuery('.graph-container > div').hide();

    if (this.hasCache(name)){ //cache the stats for performance needs
        this.drawGraph(name, this.getCache(name));
    }else{
        var params = {
            'action' : 'ahm',
            'sub_action' : 'trigger_view',
            'view' : name,
            '_ajax_nonce': ahmLocal.nonce
        }
        jQuery.post(ajaxurl, params, function(data){
            if (data.status == 'success'){
                _this.drawGraph(name, data.data);
                _this.cache[name] = data.data;
                //show empty message if total is zero
                if (data.total == 0){
                    jQuery('.empty-error-log').css('display', 'table');
                } else {
                    jQuery('.empty-error-log').css('display', 'no');
                }
            }else{
                concol.log('Unable to load ' + name + ' graph data');
            }
        }, 'json');
    }
}

/**
 * Draw the actual Graph with Plot
 *
 * @var {String} type
 * @var {Object} data
 *
 * @return void
 *
 * @access public
 */
ahmGraph.prototype.drawGraph = function(type, data){
    switch(type){
        case 'line':
            jQuery('#graph-lines').empty().show();
            jQuery.plot(jQuery('#graph-lines'), data, {
                series: {
                    points: {
                        show: true,
                        radius: 5
                    },
                    lines: {
                        show: true
                    },
                    shadowSize: 0
                },
                legend: {
                    position: "ne",
                    margin: [-80, 0]
                },
                grid: {
                    color: '#646464',
                    borderColor: 'transparent',
                    borderWidth: 10,
                    hoverable: true
                },
                xaxis: {
                    tickColor: 'transparent',
                    mode: "time",
                    minTickSize: [1, "day"],
                    timeformat: "%b %d"
                },
                yaxis: {
                    min: 0,
                    tickDecimals : 'number'
                }
            });
            break;

        case 'pie':
            jQuery('#graph-pie').empty().show();
            if (data.length){
                jQuery.plot(jQuery('#graph-pie'), data, {
                    series: {
                        pie: {
                            show: true
                        }
                    },
                    grid: {
                        hoverable: true,
                        clickable: true
                    },
                    legend : {
                        labelFormatter: function(label, series) {
                            // series is the series object for the label
                            return label + '  (' + series.percent.toFixed(2) + '%)';
                        },
                        sorted : 'ascending'
                    }
                });
            }
            break;

        default:
            break;
    }
    //unlock the control panel
    this.parent.getView('control').unLockControlPanel();
}

/**
 * Check if graph data cache exists
 *
 * @var {String} name
 *
 * @return {Boolean}
 */
ahmGraph.prototype.hasCache = function(name){
    return (this.cache[name] !== null ? true : false);
}

/**
 * Get graph data cache
 *
 * @var {String} name
 *
 * @return {Mixed}
 */
ahmGraph.prototype.getCache = function(name){
    return (this.hasCache(name) ? this.cache[name] : null);
}

/**
 * Display tooltip
 *
 * @var {Int}    x
 * @var {Int}    y
 * @var {String} contents
 *
 * @return void
 *
 * @access public
 */
ahmGraph.prototype.showTooltip = function (x, y, contents) {
    jQuery('<div id="tooltip">' + contents + '</div>').css({
        top: y + 10,
        left: x + 10
    }).appendTo('body').fadeIn();
}