<?php
if(count($tpl['location_arr']) > 0)
{
	$show_period = 'false';
	if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
	{
		$show_period = 'true';
	}
	foreach($tpl['location_arr'] as $k => $v)
	{
		$arrival_hour = $arrival_minute = null;
		$departure_hour = $departure_minute = null;
		$arrival_time = null;
		$departure_time = null;
		if(isset($tpl['sl_arr']))
		{
			if(isset($tpl['sl_arr'][$v['city_id']]))
			{
				if(!empty($tpl['sl_arr'][$v['city_id']]['departure_time']))
				{
					list($departure_hour, $departure_minute,) = explode(":", $tpl['sl_arr'][$v['city_id']]['departure_time']);
					if($show_period == 'true') {
						$departure_time = date('h:iA', strtotime(date('Y-m-d'). ' '. $tpl['sl_arr'][$v['city_id']]['departure_time']));
					} else {
						$departure_time = date('H:i', strtotime(date('Y-m-d'). ' '. $tpl['sl_arr'][$v['city_id']]['departure_time']));
					}
				}
				if(!empty($tpl['sl_arr'][$v['city_id']]['arrival_time']))
				{
					list($arrival_hour, $arrival_minute,) = explode(":", $tpl['sl_arr'][$v['city_id']]['arrival_time']);
					if($show_period == 'true') {
						$arrival_time = date('h:iA', strtotime(date('Y-m-d'). ' '. $tpl['sl_arr'][$v['city_id']]['arrival_time']));
					} else {
						$arrival_time = date('H:i', strtotime(date('Y-m-d'). ' '. $tpl['sl_arr'][$v['city_id']]['arrival_time']));
					}
				}
			}
		}
		
		?>
		<div class="row form-group">
			<label class="col-md-4 col-sm-12 control-label"><?php echo pjSanitize::clean($v['name']); ?>:</label>
			<div class="col-md-8 col-sm-12">				
				<?php 
				if($k > 0)
				{
					?>
					<div class="row form-group">
						<div class="col-md-4 col-sm-6">
							<?php __('lblArrivalTime'); ?>:
						</div>
						<div class="col-md-8 col-sm-6">
							<div class="input-group">
								<input name="arrival_time_<?php echo $v['city_id']?>" value="<?php echo $arrival_time;?>" class="pj-timepicker form-control"/>
							
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
							</div>
						</div>
					</div>
					<?php
				}
				if($k < count($tpl['location_arr']) - 1)
				{
					?>
					<div class="row form-group">
						<div class="col-md-4 col-sm-6">
							<?php __('lblDepartureTime'); ?>:
						</div>
						<div class="col-md-8 col-sm-6">
							<div class="input-group">
								<input name="departure_time_<?php echo $v['city_id']?>" value="<?php echo $departure_time;?>" class="pj-timepicker form-control"/>
							
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span> 
							</div>
						</div>
					</div>
					<?php
				} 
				?>					
			</div>			
		</div>
		<?php
	}
}
?>