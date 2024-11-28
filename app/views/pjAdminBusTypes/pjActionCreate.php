<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoAddBusTypeTitle');?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->
		<?php $desc = str_replace("{SIZE}", ini_get('post_max_size'), __('infoAddBusTypeDesc', true, false));?>
        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php echo $desc;?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBusTypes&amp;action=pjActionCreate" method="post" id="frmCreateBusType" autocomplete="off" enctype="multipart/form-data">
            		<input type="hidden" name="bus_type_create" value="1" />
                    
                    <div class="row">
                    	<div class="col-md-5 col-sm-12">
                    		<?php
					    	foreach ($tpl['lp_arr'] as $v)
					    	{
					        	?>
					            <div class="form-group pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? NULL : 'none'; ?>">
					                <label class="control-label"><?php __('lblName');?></label>
					                                        
					                <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group' : '';?>" data-index="<?php echo $v['id']; ?>">
										<input type="text" class="form-control<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" name="i18n[<?php echo $v['id']; ?>][name]" data-msg-required="<?php __('fd_field_required', false, true);?>">	
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
											<input type="checkbox" class="onoffswitch-checkbox" id="use_map" name="use_map" checked>
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
                    		<div class="form-group bsUseMapYes">
                                <label class="control-label"><?php __('lblSeatsMap', false, true); ?></label>

                                <div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> <?php __('lblSelectImage');?></span>
                                        <span class="fileinput-exists"><?php __('lblChangeImage');?></span><input name="seats_map" id="seats_map" type="file" class="required" data-msg-required="<?php __('fd_field_required', false, true);?>"></span>
                                        <span class="fileinput-filename"></span>

                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
                                    </div>
                                </div>
                            </div><!-- /.form-group -->
                            
                            <div class="form-group bsUseMapNo" style="display:none;">
                                <label class="control-label"><?php __('lblSeatsCount', false, true); ?></label>

                                <div>
                                    <input type="text" name="seats_count" id="seats_count" class="form-control touchspin3 required" data-msg-required="<?php __('fd_field_required', false, true);?>"/>
                                </div>
                            </div><!-- /.form-group -->
                    	</div>
                    </div>
                    						
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
myLabel.field_required = "<?php __('fd_field_required'); ?>";
</script>