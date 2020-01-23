<form class="form-inline" id="_log_selectors_form">

  <div class="row">

    <div class="_selector col-md-3" style="display:none">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Version</div>
                <select id="_sel_version" class="selectpicker" data-live-search="true" data-width="100%">
                </select>
            </div>
        </div>
    </div>

    <div class="_selector col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Group</div>
                <select id="_sel_group" class="selectpicker" data-live-search="true" data-width="100%">
                </select>
            </div>
        </div>
    </div>

    <div class="_selector col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Type</div>
                <select id="_sel_type" class="selectpicker" data-live-search="true" data-width="100%">
                </select>
            </div>
        </div>
    </div>

    <div class="_selector col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Device</div>
                <select id="_sel_device" class="selectpicker" data-live-search="true" data-width="100%">
                </select>
            </div>
        </div>
    </div>

    <div class="_selector col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Time</div>
                <select id="_sel_stamp" class="selectpicker" data-live-search="true" data-width="100%" multiple>
                </select>
            </div>
        </div>
    </div>

    <div class="_selector col-md-1">
        <button type="button" id="_btn_add_log" class="btn btn-default" style="height: 32px" disabled>
            <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color: green"></span>
        </button>
    </div>

  </div>
</form>

<br/>
<br/>

<?php
function _logs_print_table_structure($id = null, $read_only = false) {
    $id_str = is_null($id)? '' : sprintf('id="%s"', $id);
    ?>
    <h4>Selected logs:</h4>
    <table <?php echo $id_str ?> class="_logs_list table table-striped table-condensed text-center">
        <tr>
          <th style="display:none">_key</th>
          <th style="display:none">_color</th>
          <th class="col-md-1 text-center">Color</th>
          <th class="col-md-3 text-center">Group</th>
          <th class="col-md-2 text-center">Type</th>
          <th class="col-md-3 text-center">Device</th>
          <th class="col-md-3 text-center">Time</th>
        </tr>
    </table>
    <?php
}

_logs_print_table_structure('_main_table');
?>


<button type="button" id="_btn_fetch_logs" class="btn btn-primary" style="float: right" disabled>
    <span class="fa fa-download" aria-hidden="true"></span>
    Fetch logs
</button>


<script type="text/javascript">
$('#_sel_version').on('changed.bs.select', function(){
    let _ = undefined;
    let v = "<?php echo $LOGS_VERSION ?>";
    let [_v, _g, _t, _d, _s] = filter_keys(v);
    apply_keys(_, _g, [], [], []);
    $('#_sel_group').selectpicker('val', []);
});

$('#_sel_group').on('changed.bs.select', function (){
    let _ = undefined;
    let v = "<?php echo $LOGS_VERSION ?>";
    let g = $('#_sel_group').val();
    let [_v, _g, _t, _d, _s] = filter_keys(v, g);
    apply_keys(_, _, _t, [], []);
    $('#_sel_type').selectpicker('val', []);
});

$('#_sel_type').on('changed.bs.select', function (){
    let _ = undefined;
    let v = "<?php echo $LOGS_VERSION ?>";
    let g = $('#_sel_group').val();
    let t = $('#_sel_type').val();
    let [_v, _g, _t, _d, _s] = filter_keys(v, g, t);
    apply_keys(_, _, _, _d, []);
    $('#_sel_device').selectpicker('val', []);
});

$('#_sel_device').on('changed.bs.select', function (){
    let _ = undefined;
    let v = "<?php echo $LOGS_VERSION ?>";
    let g = $('#_sel_group').val();
    let t = $('#_sel_type').val();
    let d = $('#_sel_device').val();
    let [_v, _g, _t, _d, _s] = filter_keys(v, g, t, d);
    apply_keys(_, _, _, _, _s);
    $('#_sel_stamp').selectpicker('val', []);
});

$('#_sel_stamp').on('changed.bs.select', function (){
    let s = $('#_sel_stamp').val();
    if (s && s.length > 0)
        $('#_btn_add_log').prop('disabled', false);
    else
        $('#_btn_add_log').prop('disabled', true);
});

function filter_keys(version, group, type, device) {
    let keys = window._DIAGNOSTICS_LOGS_KEYS;
    // apply filter
    let _versions = [];
    let _groups = [];
    let _types = [];
    let _devices = [];
    let _stamps = [];
    keys.forEach(function(k){
        let [_v, _g, _t, _d, _s] = k.split('__');
        if (version != undefined && _v != version) return;
        if (group != undefined && _g != group) return;
        if (type != undefined && _t != type) return;
        if (device != undefined && _d != device) return;
        _versions.push(_v);
        _groups.push(_g);
        _types.push(_t);
        _devices.push(_d);
        _stamps.push([k, _s]);
    });
    return [
        Array.from(new Set(_versions)),
        Array.from(new Set(_groups)),
        Array.from(new Set(_types)),
        Array.from(new Set(_devices)),
        _stamps
    ];
}

function apply_keys(versions, groups, types, devices, stamps){
    // refill selects
    if (versions != undefined) {
        $('#_sel_version').empty();
        versions.forEach(function(e){
            $('#_sel_version').append(new Option(e, e));
        });
    }
    if (groups != undefined) {
        $('#_sel_group').empty();
        groups.forEach(function(e){
            $('#_sel_group').append(new Option(e, e));
        });
    }
    if (types != undefined) {
        $('#_sel_type').empty();
        types.forEach(function(e){
            $('#_sel_type').append(new Option(e, e));
        });
    }
    if (devices != undefined) {
        $('#_sel_device').empty();
        devices.forEach(function(e){
            $('#_sel_device').append(new Option(e, e));
        });
    }
    if (stamps != undefined) {
        $('#_sel_stamp').empty();
        stamps.forEach(function(e){
            let datetime = new Date(parseInt(e[1]) * 1000).toISOString().slice(0, 19);
            $('#_sel_stamp').append(new Option(datetime, e[0]));
        });
    }
    // refresh select
    $('#_sel_version').selectpicker('refresh');
    $('#_sel_group').selectpicker('refresh');
    $('#_sel_type').selectpicker('refresh');
    $('#_sel_device').selectpicker('refresh');
    $('#_sel_stamp').selectpicker('refresh');
}

function get_listed_logs(seek){
    let tab_data = tableToObject('._logs_list#_main_table');
    if (seek != undefined) {
        return tab_data.map(e => e[seek]);
    }
    return tab_data;
}

function get_log_info(key, param){
    let tab_data = get_listed_logs();
    for (let i = 0; i < tab_data.length; i++) {
        let tab_row = tab_data[i];
        if (tab_row['_key'] == key){
            return tab_row[param];
        }
    }
}

$('#_btn_add_log').on('click', function(){
    let _get_keys = [
        <?php
        $_get_keys = array_key_exists('keys', $_GET)? explode(',', $_GET['keys']) : '';
        echo implode(', ', array_map(function($k){return sprintf('"%s"', $k);}, $_get_keys));
        ?>
    ];
    let _sel_keys = ($('#_sel_stamp').val() != null)? $('#_sel_stamp').val() : [];
    // get the list of keys already in the table
    let keys = Array.from(new Set(
        get_listed_logs('_key').concat(_sel_keys).concat(_get_keys)
    ));
    // clear table
    $('._logs_list > tbody tr._row').remove();
    // get colors
    let colors = [
        'red',
        'orange',
        'yellow',
        'green',
        'blue',
        'purple',
        'grey'
    ];
    let _color = c => '<span class="fa fa-stop" aria-hidden="true" style="font-size: 16px; color: {0}"></span>'.format(window.chartColors[colors[c]]);
    // add logs to the list
    keys.forEach(function(k, i){
        let c_i = i % colors.length;
        let [_v, _g, _t, _d, _s] = k.split('__');
        _dt = new Date(parseInt(_s) * 1000).toISOString().slice(0, 19);
        $('._logs_list > tbody').append(
            `<tr class="_row">
                <td style="display:none">{0}</td>
                <td id="_color_hex" style="display:none">{1}</td>
                <td id="_color">{2}</td>
                <td>{3}</td>
                <td>{4}</td>
                <td>{5}</td>
                <td>{6}</td>
            </tr>`.format(
                k, window.chartColors[colors[c_i]].slice(4, -1), _color(c_i), _g, _t, _d, _dt
            )
        );
    });
    // clear stamps selection
    $('#_sel_stamp').val([]);
    $('#_sel_stamp').trigger('changed.bs.select');
    $('#_sel_stamp').selectpicker('refresh');
    // pull general info about the log
    fetch_log_data('/general');
});

function fetch_log_data(seeks, on_step, on_success){
    // arguments
    if (!Array.isArray(seeks))
        seeks = [seeks];
    // get list of keys
    let keys = get_listed_logs('_key');
    if (keys.length <= 0) return;
    // get args
    let _on_step_fcn = on_step || function(){};
    let _on_success_fcn = on_success || function(){};
    // create destinations
    let to_load = [];
    keys.forEach(function(key) {
        seeks.forEach(function(seek){
            if (!window._DIAGNOSTICS_LOGS_DATA.hasOwnProperty(key)) {
                window._DIAGNOSTICS_LOGS_DATA[key] = {};
            }
            to_load.push({key: key, seek: seek});
        });
    });
    let total = to_load.length;
    window._DIAGNOSTICS_LOADING_PROGRESS = 1;
    let _pbar_next = function(q){return 100 * (total - q.length) / total};
    // define task function
    let _fetch = function(queue){
        // base case, nothing left in the queue
        if (queue.length === 0){
            window._DIAGNOSTICS_LOADING_PROGRESS = 100;
            return _on_success_fcn();
        }
        // get next element from queue
        let job = queue.pop();
        // base case, nothing to do
        if (window._DIAGNOSTICS_LOGS_DATA.hasOwnProperty(job.key) &&
            window._DIAGNOSTICS_LOGS_DATA[job.key].hasOwnProperty(job.seek)) {
            // run step success function
            _on_step_fcn(job.key, job.seek);
            // update progress bar
            window._DIAGNOSTICS_LOADING_PROGRESS = _pbar_next(queue);
            // move to the next job
            return _fetch(queue);
        }
        // get extra info
        let api_info = JSON.parse('<?php echo json_encode($api_info) ?>');
        // call API
        smartAPI('data', 'get', {
            'arguments': {
                'database': '<?php echo $logs_db_name ?>',
                'key': job.key,
                'seek': job.seek
            },
            'block': false,
            'confirm': false,
            ...api_info,
            'on_success': function (res) {
                window._DIAGNOSTICS_LOGS_DATA[job.key][job.seek] = res['data']['value'];
                // run step success function
                _on_step_fcn(job.key, job.seek);
                // update progress bar
                window._DIAGNOSTICS_LOADING_PROGRESS = _pbar_next(queue);
                // move to the next job
                return _fetch(queue);
            }
        });
    };
    // start queue
    _fetch(to_load);
}

$(document).on('ready', function(){
    window._DIAGNOSTICS_LOADING_PROGRESS = 1;
    // get extra info
    let api_info = JSON.parse('<?php echo json_encode($api_info) ?>');
    // fetch list of keys
    smartAPI('data', 'list', {
        'arguments': {
            'database': '<?php echo $logs_db_name ?>'
        },
        'block': false,
        'confirm': false,
        ...api_info,
        'on_success': function(res){
            window._DIAGNOSTICS_LOGS_KEYS = res['data']['keys'];
            // trigger the changed event on the version selector in order to populate the group selector
            $('#_sel_version').trigger('changed.bs.select');
            // trigger click on add_log in order to consume keys loaded from _GET
            $('#_btn_add_log').trigger('click');
            // update progress bar
            window._DIAGNOSTICS_LOADING_PROGRESS = 100;
        }
    });
});
</script>