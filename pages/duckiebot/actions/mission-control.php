<?php
use \system\classes\Core as Core;
use \system\classes\Configuration as Configuration;
use \system\classes\Database as Database;
?>

<script src="<?php echo Core::getJSscriptURL('jquery-ui-1.11.1.js', 'duckietown'); ?>"></script>
<script src="<?php echo Core::getJSscriptURL('packery.pkgd.min.js', 'duckietown'); ?>"></script>
<script src="<?php echo Core::getJSscriptURL('draggabilly.pkgd.min.js', 'duckietown'); ?>"></script>

<?php
// the param 'mission' is mandatory
if( !isset($_GET['mission']) ){
    Core::redirectTo('');
}
$mission_name = strtolower($_GET['mission']);

// define parameters for the mission control grid
$grid_width = 966; // do not use 970px to accomodate for differences between browsers
$resolution = 8;
$block_gutter = 10;
$block_border_thickness = 1;

// get name of the current duckiebot
$duckiebotName = $GLOBALS['_duckietown_duckiebot_veh'];

// read mission details
$db_name = sprintf("veh_%s_mission", $duckiebotName);
$db = new Database( 'duckietown', $db_name );
$res = $db->read($mission_name);
if( !$res['success'] ){
    Core::throwError( $res['data'] );
}
$mission_control_grid = $res['data'];

// define allowed block sizes
$sizes = [
    [1,1],
    [1,2],
    [1,3],
    [2,2],
    [2,4],
    [3,8],
    [4,8],
    [6,8],
    [8,8]
];

// create mission control grid
$mission_control = new MissionControl(
    "duckiebot-mission-control-grid",
    $grid_width,
    $resolution,
    $block_gutter,
    $block_border_thickness,
    $sizes,
    $mission_control_grid['blocks']
);
?>

<div style="width:100%; margin:auto">

	<table style="width:100%; margin-bottom:42px">
		<tr>
			<td colspan="4" style="border-bottom:1px solid #ddd">
				<h2>
                    Mission Control
                </h2>
			</td>
		</tr>
        <tr>
			<td class="text-left" style="width:20%; padding-top:10px">
                <i class="fa fa-car" aria-hidden="true"></i> Vehicle:
                <strong><?php echo $duckiebotName ?></strong>
			</td>
            <td class="text-center" style="width:30%; padding-top:10px">
                <i class="fa fa-object-ungroup" aria-hidden="true"></i> Mission:
                <strong><?php echo $mission_name ?></strong>
			</td>
            <td class="text-center" style="width:30%; padding-top:10px">
                <span id="duckiebot_status">
                    <i class="fa fa-spinner fa-pulse"></i> Connecting...
                </span>
			</td>
            <td class="text-right" style="width:20%; padding-top:10px">
                <span id="duckiebot_bridge_status">
                    <i class="fa fa-spinner fa-pulse"></i> Connecting...
                </span>
			</td>
		</tr>
	</table>

    <?php
    $mission_control->create();
    ?>

    <script type="text/javascript">

        function duckiebot_status_callback(result){
            if( result.data.online ){
                $('#duckiebot_status').html('<span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="color:green"></span> Status: <strong>Online</strong>');
            }else{
                $('#duckiebot_status').html('<span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="color:red"></span> <strong>Offline</strong>');
            }
        }//duckiebot_status_callback

        $( document ).ready(function() {
            window.mission_control_page_blocks_data = {};

            // Connect to ROS
            // TODO: PORT hard-coded
            window.ros = new ROSLIB.Ros({
                url : "ws://<?php echo str_replace('/', '', str_replace('http://', '', Configuration::$BASE_URL) ); ?>:42003"
                // url : "ws://192.168.2.53:9090"
            });
            ros.on('connection', function() {
                console.log('Connected to websocket server.');
                $('#duckiebot_bridge_status').html('<span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="color:green"></span> Bridge: <strong>Connected</strong>');
                $(document).trigger('ROSBridge_connected');
            });
            ros.on('error', function(error) {
                console.log('Error connecting to websocket server: ', error);
                $('#duckiebot_bridge_status').html('<span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="color:red"></span> Bridge: <strong>Error</strong>');
            });
            ros.on('close', function() {
                console.log('Connection to websocket server closed.');
                $('#duckiebot_bridge_status').html('<span class="glyphicon glyphicon-off" aria-hidden="true" style="color:red"></span> Bridge: <strong>Closed</strong>');
            });

            // is online check
    		var url = '<?php echo Configuration::$BASE_URL ?>web-api/<?php echo Configuration::$WEBAPI_VERSION ?>/duckiebot/status/json?name=<?php echo $duckiebotName ?>&token=<?php echo $_SESSION["TOKEN"] ?>';
    		callAPI( url, false, false, duckiebot_status_callback, true );
        });
    </script>

</div>
