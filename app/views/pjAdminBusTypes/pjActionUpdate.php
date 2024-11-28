<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoUpdateBusTypeTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoUpdateBusTypeDesc', true, false));?>
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
    	        case in_array($error_code, array('ABT01', 'ABT03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('ABT04', 'ABT06', 'ABT08', 'ABT09', 'ABT10', 'ABT11', 'ABT12')):
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
    	<div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBusTypes&amp;action=pjActionUpdate" method="post" id="frmUpdateBusType" autocomplete="off" enctype="multipart/form-data">
            		<input type="hidden" name="bus_type_update" value="1" />
					<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
                    <div class="row">
                    	<div class="col-md-5 col-sm-12">
                    		<?php
					    	foreach ($tpl['lp_arr'] as $v)
					    	{
					        	?>
					            <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
					                <label class="control-label"><?php __('lblName');?></label>
					                                        
					                <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
										<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][name]" value="<?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['name'])); ?>" data-msg-required="<?php __('fd_field_required', false, true);?>">	
										<?php if ($tpl['is_flag_ready']) : ?>
										<span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
										<?php endif; ?>
									</div>
					            </div>
					            <?php
					        }
					        ?>
                    	</div>
                    	<div class="col-md-3 col-sm-4">
                    		<div class="form-group">
								<label class="control-label"><?php __('lblUseSeatsMap'); ?></label>
							
								<div class="clearfix">
									<div class="switch onoffswitch-data pull-left">
										<div class="onoffswitch onoffswitch-order">
											<input type="checkbox" class="onoffswitch-checkbox" id="use_map" name="use_map" data-has_map="<?php echo $tpl['arr']['use_map'] == 'T' && is_file($tpl['arr']['seats_map']) ? 1 : 0;?>" <?php echo $tpl['arr']['use_map'] == 'T' ? 'checked="checked"' : '';?>>
											<label class="onoffswitch-label" for="use_map">
												<span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true);?>" data-off="<?php __('_yesno_ARRAY_F', false, true);?>"></span>
												<span class="onoffswitch-switch"></span>
											</label>
										</div>
									</div>
								</div><!-- /.clearfix -->
							</div>
                    	</div>
                    	<div class="col-md-4 col-sm-8">
                    		<div class="pjUploadMapWrap">
                    			<?php if ($tpl['arr']['use_map'] == 'T' && is_file($tpl['arr']['seats_map'])) { ?>
                    				<div class="form-group pjDeleteMapWrap">
			                                <label class="control-label"><?php __('lblSeatsMap', false, true); ?></label>
		                                <div>
											<button type="button" class="btn btn-danger btn-outline pj-delete-map" data-id="<?php echo $tpl['arr']['id']?>"><?php __('btnDeleteMap'); ?></button>
										</div>			                                
		                            </div><!-- /.form-group -->
		                       <?php } ?>
		                       
		                       	<div class="form-group bsUseMapYes" style="display: <?php echo ($tpl['arr']['use_map'] == 'T' && !is_file($tpl['arr']['seats_map'])) ? '' : 'none';?>">
	                                <label class="control-label"><?php __('lblSeatsMap', false, true); ?></label>
	
	                                <div>
	                                    <div class="fileinput fileinput-new" data-provides="fileinput">
	                                        <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> <?php __('lblSelectImage');?></span>
	                                        <span class="fileinput-exists"><?php __('lblChangeImage');?></span><input name="seats_map" id="seats_map" type="file" class="<?php echo $tpl['arr']['use_map'] == 'T' && !is_file($tpl['arr']['seats_map']) ? 'required' : '';?>" data-msg-required="<?php __('fd_field_required', false, true);?>"></span>
	                                        <span class="fileinput-filename"></span>
	
	                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
	                                    </div>
	                                </div>
	                            </div><!-- /.form-group -->
                    		</div>                            
                            <div class="form-group bsUseMapNo" style="display: <?php echo $tpl['arr']['use_map'] == 'T' ? 'none' : '';?>;">
                                <label class="control-label"><?php __('lblSeatsCount', false, true); ?></label>

                                <div>
                                    <input type="text" name="seats_count" id="seats_count" value="<?php echo count($tpl['seat_arr']) > 0 ? count($tpl['seat_arr']) : null; ?>" class="form-control touchspin3 <?php echo $tpl['arr']['use_map'] == 'T' ? '' : 'required';?>" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                                </div>
                            </div><!-- /.form-group -->
                    	</div>
                    </div>
                    					
                    <?php if ($tpl['arr']['use_map'] == 'T' && is_file($tpl['arr']['seats_map'])) { 
                    	$map = $tpl['arr']['seats_map'];
                    	$size = getimagesize($map);
                    	?>
                    	<div id="boxMap">
                    		<div class="form-group">
								<div class="bsMapHolder">
									<div id="mapHolder" style="position: relative; overflow: hidden; width: <?php echo $size[0]; ?>px; height: <?php echo $size[1]; ?>px; margin: 0 auto;">
										<img id="map" src="<?php echo $map; ?>" alt="" style="margin: 0; border: none; position: absolute; top: 0; left: 0; z-index: 500" />
										<?php
										foreach ($tpl['seat_arr'] as $seat)
										{
											?><span rel="hi_<?php echo $seat['name']; ?>" title="<?php echo $seat['name']; ?>" class="rect empty" style="position: absolute; width: <?php echo $seat['width']; ?>px; height: <?php echo $seat['height']; ?>px; left: <?php echo $seat['left']; ?>px; top: <?php echo $seat['top']; ?>px; line-height: <?php echo $seat['height']; ?>px"><span class="bsInnerRect" data-name="hi_<?php echo $seat['id']; ?>"><?php echo stripslashes($seat['name']); ?></span></span><?php
										}
										?>
									</div>
									<div class="form-group text-center">
										<div>
											<input type="hidden" id="number_of_seats" name="number_of_seats" value="" class="required"/>
										</div>
									</div>
								</div>
							</div>
							<div id="hiddenHolder">
								<?php
								foreach ($tpl['seat_arr'] as $seat)
								{
									?><input id="hi_<?php echo $seat['name']; ?>" type="hidden" name="seats[]" value="<?php echo join("|", array($seat['id'], $seat['width'], $seat['height'], $seat['left'], $seat['top'], $seat['name'])); ?>" /><?php
								}
								?>
							</div>
							
							<div align="center">
								<button type="button" id="pj_delete_seat" class="btn btn-danger btn-outline" style="display: none;"/></button>
							</div>
						</div>
                    <?php } ?>	
                    <div class="hr-line-dashed"></div>

                    <div class="clearfix">
                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                            <span class="ladda-label"><?php __('btnSave'); ?></span>
                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        </button>
                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBusTypes&action=pjActionIndex"><?php __('btnCancel'); ?></a>
                    </div><!-- /.clearfix -->
                </form>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>

<?php if ($tpl['is_flag_ready']) : ?>
<script type="text/javascript">
var pjCmsLocale = pjCmsLocale || {};
pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
</script>
<?php endif; ?>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.localeId = "<?php echo $controller->getLocaleId(); ?>";
myLabel.field_required = "<?php __('bs_field_required'); ?>";
myLabel.seats_required = "<?php __('bs_seats_required'); ?>";

myLabel.alert_del_map_title = <?php x__encode('btnDeleteMap'); ?>;
myLabel.alert_del_map_text = <?php x__encode('lblDeleteMapConfirm'); ?>;
myLabel.btn_delete = <?php x__encode('btnDelete'); ?>;
myLabel.btn_cancel = <?php x__encode('btnCancel'); ?>;
</script>