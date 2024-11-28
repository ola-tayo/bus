<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-10">
				<h2><?php __('infoScheduleBookingsTitle'); ?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoScheduleBookingsDesc'); ?></p>
	</div><!-- /.col-md-12 -->
</div>
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<?php if (pjAuth::factory('pjAdminBuses', 'pjActionTime')->hasAccess() ) { ?>
				<h3><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTime&amp;id=<?php echo $controller->_get->toInt('bus_id');?>"><?php __('lblBus')?>:&nbsp;<?php echo $tpl['bus_arr']['route']?>, <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?> - <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['arrival_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a> <?php __('lblOn');?> <?php echo $controller->_get->toString('date');?></h3>
			<?php } else { ?>
				<h3><a href="javascript:void(0);"><?php __('lblBus')?>:&nbsp;<?php echo $tpl['bus_arr']['route']?>, <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['departure_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?> - <?php echo pjDateTime::formatTime(date('H:i:s', strtotime($tpl['bus_arr']['arrival_time'])), 'H:i:s', $tpl['option_arr']['o_time_format'])?></a> <?php __('lblOn');?> <?php echo $controller->_get->toString('date');?></h3>
			<?php } ?>			
			<div class="tabs-container tabs-reservations m-b-lg">
				<ul class="nav nav-tabs" role="tablist">
		            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionBookings&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo $controller->_get->toString('date');?>"><?php __('lblPassengersList');?></a></li>
		            <?php if (pjAuth::factory('pjAdminSchedule', 'pjActionSeats')->hasAccess() ) { ?>
		            	<li role="presentation" class=""><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionSeats&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo $controller->_get->toString('date');?>"><?php __('lblSeatsList'); ?></a></li>
		            <?php } ?>
		        </ul>
		       <div class="tab-content">
		            <div role="tabpanel" class="tab-pane active">
		                <div class="panel-body">
							<form action="" method="get" class="frm-filter" id="frmSchedule">
								<div class="row">
									<div class="col-md-6 col-sm-6">
										<select id="location_id" name="location_id" class="form-control" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionGetBookings&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo pjDateTime::formatDate($controller->_get->toString('date'), $tpl['option_arr']['o_date_format'], 'Y-m-d');?>">
											<option value="">-- <?php __('lblStartLocation') ?> --</option>
											<?php
											foreach($tpl['location_arr'] as $k => $v)
											{
												if($k <= count($tpl['location_arr']) - 2)
												{
													?>
													<option value="<?php echo $v['city_id']?>"><?php echo pjSanitize::clean($v['location']);?></option>
													<?php
												}
											} 
											?>
										</select>
				                    </div>
				                    <div class="col-md-6 col-sm-6 text-right">
				                    	<div class="form-group">
											<a target="_blank" id="bs_print_booking" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionPrintBookings&amp;bus_id=<?php echo $controller->_get->toInt('bus_id');?>&amp;date=<?php echo pjDateTime::formatDate($controller->_get->toString('date'), $tpl['option_arr']['o_date_format'], 'Y-m-d');?>" class="btn btn-primary"><i class="fa fa-print"></i> <?php __('lblPrintList');?></a>
										</div>
				                    </div>
								</div>
							</form>
							<div class="bs-loader-outer">
								<div class="bs-loader"></div>
								<div id="boxBookings"><?php include PJ_VIEWS_PATH . 'pjAdminSchedule/elements/getBookings.php'; ?></div>
							</div>
						</div>
	            	</div>
	   			</div>
			</div>
		</div>
	</div>
</div>