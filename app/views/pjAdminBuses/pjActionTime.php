<?php
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true); 
$show_period = 'false';
if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
{
	$show_period = 'true';
}
?>
<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo $tpl['date_format']; ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <h2><?php __('infoUpdateTimeTitle');?></h2>
            </div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoUpdateTimeDesc', true, false));?>
        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php echo $desc;?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    	    $titles = __('error_titles', true);
    	    $bodies = __('error_bodies', true);
    	    switch (true)
    	    {
    	        case in_array($error_code, array('ABS01', 'ABS03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('ABS04', 'ABS06', 'ABS08', 'ABS09', 'ABS10', 'ABS11', 'ABS12')):
    				?>
    				<div class="alert alert-danger">
    					<i class="fa fa-exclamation-triangle m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
    		}
    	}
    	?>
    	<h3><?php __('lblRoute'); ?>: <?php echo pjSanitize::html(@$tpl['route_arr']['title']);?></h3>
    	<div class="tabs-container tabs-reservations m-b-lg">
			<ul class="nav nav-tabs" role="tablist">
	            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTime&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblTimes');?></a></li>
	            <li role="presentation" class=""><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionNotOperating&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblNotOperating'); ?></a></li>
	            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTicket&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblTickets'); ?></a></li>
	            <li role="presentation" class=""><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('lblPrices'); ?></a></li>
	        </ul>
	       <div class="tab-content">
	            <div role="tabpanel" class="tab-pane active">
	                <div class="panel-body">
	                	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionTime" method="post" id="frmUpdateTime" autocomplete="off">
		            		<input type="hidden" name="bus_update" value="1" />
		                    <input type="hidden" id="id" name="id" value="<?php echo $tpl['arr']['id']?>" />
		                    <div class="row form-group">
		                    	<label class="col-md-4 col-sm-12 control-label"><?php __('lblBusType', false, true); ?> <span class="bus-type-tooltip" data-toggle="tooltip" data-placement="top" title="<?php __("lblBusTypeTip", false, true) ?>"><i class="fa fa-info-circle"></i></span></label>
		                    	<div class="col-md-8 col-sm-12">
		                    		<div>
		                    			<select name="bus_type_id" id="bus_type_id" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
											<option value="">-- <?php __('lblChoose'); ?> --</option>
											<?php
											foreach ($tpl['bus_type_arr'] as $v)
											{
												?><option value="<?php echo $v['id']; ?>"<?php echo $v['id'] == $tpl['arr']['bus_type_id'] ? ' selected="selected"' : null;?>><?php echo stripslashes($v['name']); ?>, <?php echo stripslashes($v['seats_count']); ?> <?php echo strtolower(__('lblSeats', true, false))?></option><?php
											}
											?>
										</select>
		                    		</div>
		                    	</div>
		                    </div>
		                    
		                    <div class="bs-loader-outer">
								<div class="bs-loader"></div>
								<div id="bs_bus_locations">
									<?php
									if(isset($tpl['location_arr']))
									{
										include_once PJ_VIEWS_PATH . 'pjAdminBuses/pjActionGetLocations.php';
									} 
									?>
								</div>
							</div>
							
							<div class="row">
		                    	<div class="col-sm-6 col-xs-12">
		                    		<div class="form-group">
										<label class="control-label"><?php __('lblDateFrom');?></label>
									
										<div class="input-group"> 
											<input type="text" name="start_date" id="start_date" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['start_date']));?>" class="form-control datepick required" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
		                    	</div>
		                    	<div class="col-sm-6 col-xs-12">
		                    		<div class="form-group">
										<label class="control-label"><?php __('lblDateTo');?> <span class="bus-type-tooltip" data-toggle="tooltip" data-placement="top" title="<?php __("lblPeriodTip", false, true) ?>"><i class="fa fa-info-circle"></i></span></label>
									
										<div class="input-group"> 
											<input type="text" name="end_date" id="end_date" value="<?php echo date($tpl['option_arr']['o_date_format'], strtotime($tpl['arr']['end_date']));?>" class="form-control datepick required" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
		                    	</div>
		                    </div>
		                    
		                    <div class="row">
		                    	<div class="col-xs-12">
		                    		<div class="form-group">
		                    			<label class="control-label"><?php __('lblRecurring');?></label>
		                    			<?php 
		                    			$weekdays = __('weekdays', true, false);
		                    			$map_weekdays = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
		                    			$recurring_arr = explode('|', $tpl['arr']['recurring']);
		                    			?>
		                    			<ul class="list-unstyled list-inline">
		                    				<?php foreach ($map_weekdays as $k) { ?>
		                    					<li><input type="checkbox" class="i-checks" id="bs_weekday_<?php echo $k;?>" name="recurring[]" value="<?php echo $k;?>" <?php echo in_array($k, $recurring_arr) ? 'checked="checked"' : '';?> /> <label for="bs_weekday_<?php echo $k;?>"><?php echo $weekdays[$k];?></label></li>
		                    				<?php } ?>
		                    			</ul>
		                    		</div>
		                    	</div>
		                    </div>
		                    						
		                    <div class="hr-line-dashed"></div>
		
		                    <div class="clearfix">
		                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
		                            <span class="ladda-label"><?php __('btnSave'); ?></span>
		                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
		                        </button>
		                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBuses&action=pjActionIndex"><?php __('btnCancel'); ?></a>
		                    </div><!-- /.clearfix -->
		                </form>
	                </div>
	            </div>
	   		</div>
	   	</div>
    </div><!-- /.col-lg-12 -->
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.field_required = "<?php __('fd_field_required'); ?>";
myLabel.showperiod = <?php echo $show_period; ?>;
</script>