<?php
# @Author: Andrea F. Daniele <afdaniele>
# @Date:   Tuesday, February 13th 2018
# @Email:  afdaniele@ttic.edu
# @Last modified by:   afdaniele
# @Last modified time: Tuesday, February 13th 2018


use \system\classes\Core as Core;

?>


<style type="text/css">

body > .container{
	/* TODO: compute this based on the size of the grid + 2*toolbox_width + some padding */
    min-width: 1000px;
}

</style>

<?php
$tiles = [
	"3way" => ["0", "90", "180", "270"],
	"4way" => ["0"],
	"curve" => ["0", "90", "180", "270"],
	"grass" => ["0"],
	"parking_lot" => ["0"],
	"straight_2stop" => ["0", "90"],
	"straight_stop" => ["0", "90", "180", "270"],
	"straight" => ["0", "90"]
];

?>

<link href="<?php echo sprintf("%scss.php?package=%s&stylesheet=%s",
	\system\classes\Configuration::$BASE_URL, 'duckietown', 'duckietown_page.css'); ?>" rel="stylesheet">
<script src="<?php echo sprintf("%sjs.php?package=%s&script=%s",
	\system\classes\Configuration::$BASE_URL, 'duckietown', 'duckietown_page.js'); ?>"></script>

<!-- load JQuery 1.11.1 -->
<!-- TODO: In future versions of \compose\ this will be loaded as an auxiliary library by \compose\ -->
<script src="<?php echo sprintf("%sjs.php?package=%s&script=%s",
	\system\classes\Configuration::$BASE_URL, 'duckietown', 'jquery-ui-1.11.1.js'); ?>"></script>

<div style="width:100%; margin:auto">

	<table style="width:970px; margin:auto; border-bottom:1px solid #ddd; margin-bottom:32px">

		<tr>
			<td style="width:50%">
				<h2>Duckietown</h2>
			</td>
		</tr>

	</table>



	<table style="width:100%">
		<tr>

			<td class="side_toolbox_container side_toolbox_container_left">

				<div id="tiles_toolbox" class="tiles_toolbox_left_top">
						<?php
						foreach ($tiles as $tile => $tile_orientations):
							foreach ($tile_orientations as $orientation): ?>
								<img class="tile tile_<?php echo $orientation ?>"
									id="<?php echo $tile."_".$orientation ?>"
									src="<?php echo Core::getImageURL($tile.'_tile_plain.svg', 'duckietown'); ?>" />
							<?php
							endforeach;
						endforeach;
						?>
                        <!-- Add a hidden empty tile used to restore the grid when tiles are trashed -->
                        <img class="tile tile_0" style="display:none"
                            id="empty_0"
                            src="<?php echo Core::getImageURL('empty_plain.svg', 'duckietown'); ?>" />
				</div>

				<div id="tiles_trash" class="tiles_toolbox_left_bottom"
                    style="background-image: url('<?php echo Core::getImageURL('trashcan.png', 'duckietown') ?>')" >
                    <!-- ondrop="dragOverTrashOnDrop(event)"
                    ondragover="allowDrop(event)"
                    ondragenter="dragOverTrashOnEnter(event)"
                    ondragleave="dragOverTrashOnLeave(event)" -->

				</div>

			</td>


			<td class="text-center" style="width:100%">

				<?php
				$rows = 7;
				$columns = 5;
				?>

				<table id="town_canvas">
				<?php
				for ($i = 0; $i < $rows; $i++) {
					echo "<tr>";
					for ($j = 0; $j < $columns; $j++) {
						?>
						<td>
							<div id="slot_<?php echo $i."_".$j ?>"
								class="tile_grid_container"
								data-row="<?php echo $i ?>"
								data-column="<?php echo $j ?>">
                                <!-- ondragenter="dragOverGridOnEnter(event)"
								ondragleave="dragOverGridOnLeave(event)"> -->
                                <!-- ondragover="allowDrop(event)"
								ondrop="dragOverGridOnDrop(event)"
								ondragenter="dragOverGridOnEnter(event)"
								ondragleave="dragOverGridOnLeave(event)"> -->

                                <img class="tile" data-tile="empty"
									id="tile_<?php echo $i."_".$j ?>"
									src="<?php echo Core::getImageURL('empty_tile_plain.svg', 'duckietown'); ?>" />
							</div>
						</td>
						<?php
					}
					echo "</tr>";
				}
				?>
				</table>

			</td>


			<td class="side_toolbox_container side_toolbox_container_right">

				<div class="tiles_toolbox_right">

				</div>

			</td>

		</tr>
	</table>

</div>
