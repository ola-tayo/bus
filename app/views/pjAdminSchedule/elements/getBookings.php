<div class="form-group">
	<label><?php __('lblTotalPassengers'); ?>: </label> <?php echo (int)$tpl['total_passengers']; ?>
</div>
<?php foreach($tpl['ticket_arr'] as $v) { ?>
<div class="form-group">
	<label><?php echo pjSanitize::html($v['title']); ?>: </label> <?php echo (int)$v['total_tickets']; ?>
</div>
<?php } ?>
<div class="form-group">
	<label><?php __('lblTotalBookings'); ?>: </label> <?php echo (int)$tpl['total_bookings']; ?>
</div>
<div class="overflow pj-form form">

	<p>
		<label class="title"><?php __('lblTotalBookings'); ?>:</label>
		<span class="inline_block" >
			<label class="content"><?php echo $tpl['total_bookings']; ?></label>
		</span>
	</p>
</div>
<div class="table-responsive table-responsive-secondary">
	<table class="table table-bordered " cellspacing="0" cellpadding="0" style="width: 100%;">
		<thead>
			<tr>
				<th><?php __('lblClient');?></th>
				<th><?php __('lblPhone');?></th>
				<th><?php __('lblFrom');?></th>
				<th><?php __('lblTo');?></th>
				<th><?php __('lblTicket');?></th>
				<th><?php __('lblSeats');?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(count($tpl['booking_arr']) > 0)
			{
				$person_titles = __('personal_titles', true, false);
				foreach($tpl['booking_arr'] as $v)
				{
					$tickets = $v['tickets'];
					$cnt_tickets = count($tickets);
					$seats = join(", ", $v['seats']);
					$client_name_arr = array();
					if(!empty($v['c_title']))
					{
						$client_name_arr[] = $person_titles[$v['c_title']];
					}
					if(!empty($v['c_fname']))
					{
						$client_name_arr[] = pjSanitize::clean($v['c_fname']);
					}
					if(!empty($v['c_lname']))
					{
						$client_name_arr[] = pjSanitize::clean($v['c_lname']);
					}
					if($cnt_tickets > 1)
					{
						foreach($tickets as $k => $t)
						{
							if($k == 0)
							{
								?>
								<tr>
									<td rowspan="<?php echo $cnt_tickets;?>">
										<?php if (pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess()) { ?>
											<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']?>"><?php echo join(" ", $client_name_arr);?></a>
										<?php } else { ?>
											<?php echo join(" ", $client_name_arr);?>
										<?php } ?>
									</td>
									<td rowspan="<?php echo $cnt_tickets;?>"><?php echo $v['c_phone'];?></td>
									<td rowspan="<?php echo $cnt_tickets;?>"><?php echo pjSanitize::clean($v['from_location']);?></td>
									<td rowspan="<?php echo $cnt_tickets;?>"><?php echo pjSanitize::clean($v['to_location']);?></td>
									<td><?php echo $t;?></td>
									<td rowspan="<?php echo $cnt_tickets;?>"><?php echo $seats;?></td>
								</tr>
								<?php
							}else{
								?>
								<tr>
									<td><?php echo $t;?></td>
								</tr>
								<?php
							}
						}
					}else{
						?>
						<tr>
							<td>
								<?php if (pjAuth::factory('pjAdminBookings', 'pjActionUpdate')->hasAccess()) { ?>
									<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $v['id']?>"><?php echo join(" ", $client_name_arr);?></a>
								<?php } else { ?>
									<?php echo join(" ", $client_name_arr);?>
								<?php } ?>
							</td>
							<td><?php echo $v['c_phone'];?></td>
							<td><?php echo pjSanitize::clean($v['from_location']);?></td>
							<td><?php echo pjSanitize::clean($v['to_location']);?></td>
							<td><?php echo $tickets[0];?></td>
							<td><?php echo $seats;?></td>
						</tr>
						<?php
					}
				}
			} else {
				?>
				<tr>
					<td colspan="6"><?php __('gridEmptyResult');?></td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>