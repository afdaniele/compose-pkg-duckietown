<?php
_logs_print_table_structure();

/*
Keys used from Log:

    general:
        time
        duration

    resources_stats:
        time
        memory.pmem
        cpu.pcpu

*/
?>

<hr>

<div id="_logs_tab_resources">
    <br/>
    <h4>CPU Usage</h4>
    <div id="_logs_tab_resources_cpu">
    </div>
    <br/><br/>
    <h4>Ram Usage</h4>
    <div id="_logs_tab_resources_ram">
    </div>
</div>


<script type="text/javascript">

function _tab_resources_render_logs(){
    let seek = '/resources_stats';
    let pcpu_datasets = [];
    let pmem_datasets = [];
    get_listed_logs('_key').forEach(function(key){
        let color = get_log_info(key, '_color');
        let log_data = window._DIAGNOSTICS_LOGS_DATA[key][seek];
        let start_time = window._DIAGNOSTICS_LOGS_DATA[key]['/general'].time;
        // create datasets
        let pcpu = log_data.map(function(e){return {x: parseInt(e.time - start_time), y: e.cpu.pcpu}});
        let pmem = log_data.map(function(e){return {x: parseInt(e.time - start_time), y: e.memory.pmem}});
        // ---
        pcpu_datasets.push(get_chart_dataset({
            label: 'CPU usage (%)',
            data: pcpu,
            color: color
        }));
        pmem_datasets.push(get_chart_dataset({
            label: 'RAM usage (%)',
            data: pmem,
            color: color
        }));
    });
    // add CPU canvas to tab
    let cpu_canvas = get_empty_canvas();
    $('#_logs_tab_resources #_logs_tab_resources_cpu').append(cpu_canvas);
    // render CPU usage
    new Chart(cpu_canvas, {
        type: 'line',
        data: {
            labels: window._DIAGNOSTICS_LOGS_X_RANGE,
            datasets: pcpu_datasets
        },
        options: {
            scales: {
                yAxes: [
                    {
                        ticks: {
                            callback: function(label) {
                                return label.toFixed(0)+' %';
                            },
                            min: 0,
                            max: 100
                        },
                        gridLines: {
                            display: false
                        }
                    }
                ],
                xAxes: [
                    {
                        ticks: {
                            callback: format_time
                        }
                    }
                ]
            }
        }
    });
    // add RAM canvas to tab
    let ram_canvas = get_empty_canvas();
    $('#_logs_tab_resources #_logs_tab_resources_ram').append(ram_canvas);
    // render RAM usage
    new Chart(ram_canvas, {
        type: 'line',
        data: {
            labels: window._DIAGNOSTICS_LOGS_X_RANGE,
            datasets: pmem_datasets
        },
        options: {
            scales: {
                yAxes: [
                    {
                        ticks: {
                            callback: function(label) {
                                return label.toFixed(0)+' %';
                            },
                            min: 0,
                            max: 100
                        },
                        gridLines: {
                            display: false
                        }
                    }
                ],
                xAxes: [
                    {
                        ticks: {
                            callback: format_time
                        }
                    }
                ]
            }
        }
    });
}

// this gets executed when the tab gains focus
let _tab_resources_on_show = function(){
    let seek = '/resources_stats';
    fetch_log_data(seek, null, _tab_resources_render_logs);
};

// this gets executed when the tab loses focus
let _tab_resources_on_hide = function(){
    $('#_logs_tab_resources #_logs_tab_resources_cpu').empty();
    $('#_logs_tab_resources #_logs_tab_resources_ram').empty();
};

$('#_logs_tab_btns a[href="#resources"]').on('shown.bs.tab', _tab_resources_on_show);
$('#_logs_tab_btns a[href="#resources"]').on('hidden.bs.tab', _tab_resources_on_hide);
</script>