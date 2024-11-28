<?php
$week_start_date = $tpl['week_start_date'];
$week_end_date = $tpl['week_end_date']; 
$days = __('days', true, false);

$selected_date = strtotime($tpl['selected_date']);
$current_timestamp = strtotime(date('Y-m-d'));
?>
<div class="row form-group">
	<div class="col-xs-6">
		<a class="btn btn-primary btn-outline" id="bs_prev_week" href="javascript:void(0);" data-week_start="<?php echo date('Y-m-d', strtotime($week_start_date . " -7 days")) ?>" data-week_end="<?php echo date('Y-m-d', strtotime($week_end_date . " -7 days")) ?>"><i class="fa fa-angle-left"></i> <?php __('lblPrevWeek');?></a>
	</div>
	<div class="col-xs-6 text-right">
		<a class="btn btn-primary btn-outline" id="bs_next_week" href="javascript:void(0);" data-week_start="<?php echo date('Y-m-d', strtotime($week_end_date . " +1 days")) ?>" data-week_end="<?php echo date('Y-m-d', strtotime($week_end_date . " +7 days")) ?>"><?php __('lblNextWeek');?> <i class="fa fa-angle-right"></i></a>
	</div>
</div>
<div class="pjTblTimetableGrid">
	<table class="tblTimetableGrid" id="tblTimetableGrid" cellpadding="0" cellspacing="0" style="width: 100%;" border="0">
		<thead>
			<tr>
				<th><?php __('lblBus');?></th>
				<?php
				for($i = 0; $i < 7; $i++)
				{
					
					$week_date_timestamp = strtotime($week_start_date . " +$i days");
					?><th <?php echo $week_date_timestamp < $current_timestamp ? ($week_date_timestamp == $selected_date ? ' class="bs-passed-date bs-bold-date"' : ' class="bs-passed-date"') : ($week_date_timestamp == $current_timestamp || $week_date_timestamp == $selected_date ? ' class="bs-bold-date"' : null);?>><?php echo $days[date('w', $week_date_timestamp)]; ?><br/><?php echo pjDateTime::formatDate(date("Y-m-d", $week_date_timestamp), "Y-m-d", $tpl['option_arr']['o_date_format']) ?></th><?php
				} 
				?>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($tpl['bus_arr']) > 0)
			{
				foreach($tpl['bus_arr'] as $v)
				{
					?>
					<tr>
						<td>
							<?php if (pjAuth::factory('pjAdminBuses', 'pjActionTime')->hasAccess()) { ?>	
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&action=pjActionTime&id=<?php echo $v['id']?>"><?php echo $v['route'];?></a>
							<?php } else { ?>
								<a href="javascript:void(0);"><?php echo $v['route'];?></a>
							<?php } ?>
						</td>
						<?php
						for($i = 0; $i < 7; $i++)
						{
							$week_date_timestamp = strtotime($week_start_date . " +$i days");
							$end_date_timestamp = strtotime($v['end_date']);
							
							if($end_date_timestamp >= $week_date_timestamp)
							{
								$week_day = strtolower(date('l', $week_date_timestamp));
								$week_date_sql = date('Y-m-d', $week_date_timestamp);
								$pos = strpos($v['recurring'], $week_day);
								if(isset($tpl['date_arr'][$v['id']]))
								{
									if(in_array($week_date_sql, $tpl['date_arr'][$v['id']]))
									{
										?><td>&nbsp;</td><?php
									}else{
										if($pos === false)
										{
											?><td>&nbsp;</td><?php
										}else{
											if(isset($tpl['ticket_arr'][$v['id'] . '~:~' . $week_date_sql]))
											{
												$passengers = $tpl['ticket_arr'][$v['id'] . '~:~' . $week_date_sql];
												$passengers = $passengers . ': ' . ($passengers != 1 ? __('lblPassengers', true, false) : __('lblPassenger', true, false));
											}else{
												$passengers = '0: ' . __('lblPassengers', true, false);
											}
											?>
											<td class="text-center <?php echo $week_date_timestamp < $current_timestamp ? ' bs-passed-date' : null;?>">
												<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess()) { ?>
													<a class="timetable-tip" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&action=pjActionSeats&bus_id=<?php echo $v['id']?>&date=<?php echo pjDateTime::formatDate(date('Y-m-d', $week_date_timestamp), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo $passengers;?>"><?php echo pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a>
												<?php } else { ?>
													<a class="timetable-tip" href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo $passengers;?>"><?php echo pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a>
												<?php } ?>
											</td>
											<?php
										}	
									}
								}else{
									if($pos === false)
									{
										?><td>&nbsp;</td><?php
									}else{
										if(isset($tpl['ticket_arr'][$v['id'] . '~:~' . $week_date_sql]))
										{
											$passengers = $tpl['ticket_arr'][$v['id'] . '~:~' . $week_date_sql];
											$passengers = $passengers . ': ' . ($passengers != 1 ? __('lblPassengers', true, false) : __('lblPassenger', true, false));
										}else{
											$passengers = '0: ' . __('lblPassengers', true, false);
										}
										?>
										<td class="text-center <?php echo $week_date_timestamp < $current_timestamp ? ' bs-passed-date' : null;?>">
											<?php if (pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess()) { ?>
												<a class="timetable-tip" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&action=pjActionSeats&bus_id=<?php echo $v['id']?>&date=<?php echo pjDateTime::formatDate(date('Y-m-d', $week_date_timestamp), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo $passengers;?>"><?php echo pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a>
											<?php } else { ?>
												<a class="timetable-tip" href="javascript:void(0);" data-toggle="tooltip" data-html="true" data-placement="bottom" title="<?php echo $passengers;?>"><?php echo pjDateTime::formatTime(date('H:i:s', strtotime($v['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a>
											<?php } ?>
										</td>
										<?php
									}
								}
							}else{
								?><td>&nbsp;</td><?php
							}
						} 
						?>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td colspan="8"><?php __('gridEmptyResult');?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>