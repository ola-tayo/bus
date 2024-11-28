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
                <h2><?php __('infoAddBusTitle');?></h2>
            </div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoAddBusDesc', true, false));?>
        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php echo $desc;?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBuses&amp;action=pjActionCreate" method="post" id="frmCreateBus" autocomplete="off">
            		<input type="hidden" name="bus_create" value="1" />
                    
                    <div class="row form-group">
                    	<div class="col-sm-6 col-xs-12">
                    		<div class="form-group">
                                <label class="control-label"><?php __('lblRoute', false, true); ?></label>
                                <div>
                                    <select name="route_id" id="route_id" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
										<option value="">-- <?php __('lblChoose'); ?> --</option>
										<?php
										foreach ($tpl['route_arr'] as $v)
										{
											if(isset($tpl['route_id']) && $tpl['route_id'] == $v['id'])
											{
												?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo stripslashes($v['title']); ?></option><?php
											}else{
												?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['title']); ?></option><?php
											}
										}
										?>
									</select>
                                </div>
                            </div><!-- /.form-group -->
                    	</div>
                    	<div class="col-sm-6 col-xs-12">
                    		<div class="form-group">
                                <label class="control-label"><?php __('lblBusType', false, true); ?> <span class="bus-type-tooltip" data-toggle="tooltip" data-placement="top" title="<?php __("lblBusTypeTip", false, true) ?>"><i class="fa fa-info-circle"></i></span></label>
                                <div>
                                    <select name="bus_type_id" id="bus_type_id" class="form-control required" data-msg-required="<?php __('fd_field_required', false, true);?>">
										<option value="">-- <?php __('lblChoose'); ?> --</option>
										<?php
										foreach ($tpl['bus_type_arr'] as $v)
										{
											?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['name']); ?>, <?php echo stripslashes($v['seats_count']); ?> <?php echo strtolower(__('lblSeats', true, false))?></option><?php
										}
										?>
									</select>
                                </div>
                            </div><!-- /.form-group -->
                    	</div>
                    </div>
                    
                    <div class="bs-loader-outer">
						<div class="bs-loader"></div>
						<div id="bs_bus_locations">
							
						</div>
					</div>
					
					<div class="row">
                    	<div class="col-sm-6 col-xs-12">
                    		<div class="form-group">
								<label class="control-label"><?php __('lblDateFrom');?></label>
							
								<div class="input-group"> 
									<input type="text" name="start_date" id="start_date" class="form-control datepick required" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
                    	</div>
                    	<div class="col-sm-6 col-xs-12">
                    		<div class="form-group">
								<label class="control-label"><?php __('lblDateTo');?> <span class="bus-type-tooltip" data-toggle="tooltip" data-placement="top" title="<?php __("lblPeriodTip", false, true) ?>"><i class="fa fa-info-circle"></i></span></label>
							
								<div class="input-group"> 
									<input type="text" name="end_date" id="end_date" class="form-control datepick required" readonly="readonly" data-msg-required="<?php __('fd_field_required');?>" /> 
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
                    			?>
                    			<ul class="list-unstyled list-inline">
                    				<?php foreach ($map_weekdays as $k) { ?>
                    					<li><input type="checkbox" class="i-checks" id="bs_weekday_<?php echo $k;?>" name="recurring[]" value="<?php echo $k;?>" checked="checked" /> <label for="bs_weekday_<?php echo $k;?>"><?php echo $weekdays[$k];?></label></li>
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
    </div><!-- /.col-lg-12 -->
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.field_required = "<?php __('fd_field_required'); ?>";
myLabel.showperiod = <?php echo $show_period; ?>;
</script>