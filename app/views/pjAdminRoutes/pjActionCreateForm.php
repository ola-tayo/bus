<?php
$filter = __('filter', true);
$index = 'bs_' . rand(1, 999999);
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminRoutes&amp;action=pjActionCreate" method="post" id="frmCreateRoute">
	<input type="hidden" name="route_create" value="1" />
	<input type="hidden" id="index_arr" name="index_arr" value="<?php echo $index;?>" />
    <div class="panel-heading bg-completed">
        <p class="lead m-n"><?php __('infoAddRouteTitle');?></p>
    </div><!-- /.panel-heading -->

    <div class="panel-body">
    	<?php
    	foreach ($tpl['lp_arr'] as $v)
    	{
        	?>
            <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
                <label class="control-label"><?php __('lblTitle');?></label>
                                        
                <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
					<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][title]" data-msg-required="<?php __('fd_field_required', false, true);?>">	
					<?php if ($tpl['is_flag_ready']) : ?>
					<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
					<?php endif; ?>
				</div>
            </div>
            <?php
        }
        if(isset($tpl['city_arr']) && count($tpl['city_arr']) > 0) { ?>
        	<div id="bs_location_list" class="bs-location-list">
        		<?php if (!$controller->_get->check('from_id')) { ?>
        			<div class="bs-location-row" data-index="<?php echo $index;?>">
        				<label class="control-label bs-title-<?php echo $index;?>"><?php __('lblLocation'); ?> 1:</label>
        				<table width="100%">
        					<tbody>
        						<tr>
        							<td>
        								<div class="form-group">
											<select name="city_id_<?php echo $index;?>" class="form-control required bs-city">
												<option value="">-- <?php __('lblChoose'); ?>--</option>
												<?php
												foreach($tpl['city_arr'] as $k => $v)
												{
													?><option value="<?php echo $v['id'];?>"><?php echo pjSanitize::clean($v['name']);?></option><?php
												} 
												?>
											</select>
										</div>
        							</td>
        							<td width="100">
        								<div class="form-group">
	        								<a href="javascript:void(0);" class="btn btn-success btn-outline btn-sm m-l-xs location-move-icon"><i class="fa fa-arrows"></i></a>
	        								<a href="javascript:void(0);" class="btn btn-danger btn-outline btn-sm m-l-xs location-delete-icon"><i class="fa fa-trash"></i></a>
	        							</div>
        							</td>
        						</tr>
        					</tbody>
        				</table>
					</div>
        		<?php } else { 
        			foreach($tpl['city_id_arr'] as $k => $city_id)
					{
						$index = 'bs_' . rand(1, 999999);
						?>
						<div class="bs-location-row" data-index="<?php echo $index;?>">
	        				<label class="control-label bs-title-<?php echo $index;?>"><?php __('lblLocation'); ?> <?php echo $k;?>:</label>
	        				<table width="100%">
	        					<tbody>
	        						<tr>
	        							<td>
	        								<div class="form-group">
												<select name="city_id_<?php echo $index;?>" class="form-control required bs-city">
													<option value="">-- <?php __('lblChoose'); ?>--</option>
													<?php
													foreach($tpl['city_arr'] as $v)
													{
														?><option value="<?php echo $v['id'];?>"<?php echo $city_id == $v['id'] ? ' selected="selected"' : null;?>><?php echo pjSanitize::clean($v['name']);?></option><?php
													} 
													?>
												</select>
											</div>
	        							</td>
	        							<td width="100">
	        								<div class="form-group">
		        								<a href="javascript:void(0);" class="btn btn-success btn-outline btn-sm m-l-xs location-move-icon"><i class="fa fa-arrows"></i></a>
		        								<a href="javascript:void(0);" class="btn btn-danger btn-outline btn-sm m-l-xs location-delete-icon"><i class="fa fa-trash"></i></a>
		        							</div>
	        							</td>
	        						</tr>
	        					</tbody>
	        				</table>
						</div>
						<?php
					}
        		} ?>
        	</div>
        <?php } else { 
        	$label = __('lblCitiesPrompt', true, false);
			$label = str_replace("{STAG}",  '<a href="' . $_SERVER['PHP_SELF'] . '?controller=pjAdminCities&amp;action=pjActionIndex">', $label);
			$label = str_replace("{ETAG}", '</a>', $label);
        	?>
        	<div class="form-group">
        		<div class="text-warning"><?php echo $label?></div>
        	</div>
        <?php } ?>
        
        <div class="form-group">
        	<button class="btn btn-sm btn-outline btn-primary pj-add-location" type="button"><i class="fa fa-plus"></i> <?php __('btnAddLocation'); ?></button>
		</div>
        
        <div class="form-group">
            <label class="control-label"><?php __('lblStatus'); ?></label>
        
            <div class="clearfix">
                <div class="switch onoffswitch-data pull-left">
                    <div class="onoffswitch">
                        <input type="checkbox" class="onoffswitch-checkbox" id="status" name="status" checked>
                        <label class="onoffswitch-label" for="status">
                            <span class="onoffswitch-inner" data-on="<?php echo $filter['active']; ?>" data-off="<?php echo $filter['inactive']; ?>"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div><!-- /.clearfix -->
        </div>

		<div class="m-t-lg">
            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                <span class="ladda-label"><?php __('btnSave'); ?></span>
                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
            </button>
            <button type="button" class="btn btn-white btn-lg pull-right pjFdBtnCancel"><?php __('btnCancel'); ?></button>
        </div><!-- /.clearfix -->
    </div><!-- /.panel-body -->
</form>

<div id="bs_location_clone" style="display:none;">
	<div class="bs-location-row" data-index="{INDEX}">
		<label class="control-label bs-title-{INDEX}"><?php __('lblLocation'); ?> {ORDER}:</label>
		<table width="100%">
			<tbody>
				<tr>
					<td>
						<div class="form-group">
							<select name="city_id_{INDEX}" class="form-control required bs-city">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php
								foreach($tpl['city_arr'] as $k => $v)
								{
									?><option value="<?php echo $v['id'];?>"><?php echo pjSanitize::clean($v['name']);?></option><?php
								} 
								?>
							</select>
						</div>
					</td>
					<td width="100">
						<div class="form-group">
							<a href="javascript:void(0);" class="btn btn-success btn-outline btn-sm m-l-xs location-move-icon"><i class="fa fa-arrows"></i></a>
							<a href="javascript:void(0);" class="btn btn-danger btn-outline btn-sm m-l-xs location-delete-icon"><i class="fa fa-trash"></i></a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>