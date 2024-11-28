<?php 
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoContentTitle') ?></h2>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 btn-group-languages">
                <?php if ($tpl['is_flag_ready']) : ?>
				<div class="multilang"></div>
				<?php endif; ?>
        	</div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoContentDesc') ?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
	<?php
	if (isset($tpl['arr']) && is_array($tpl['arr']) && !empty($tpl['arr']))
	{
        $locale = $controller->_get->toInt('locale') ?: NULL;
        if (is_null($locale))
        {
            foreach ($tpl['lp_arr'] as $v)
            {
                if ($v['is_default'] == 1)
                {
                    $locale = $v['id'];
                    break;
                }
            }
        }
        if (is_null($locale))
        {
            $locale = @$tpl['lp_arr'][0]['id'];
        }
        ?>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                	<?php 
					$error_code = $controller->_get->toString('err');
					if (!empty($error_code))
				    {
				    	$titles = __('error_titles', true);
				    	$bodies = __('error_bodies', true);
				    	switch (true)
				    	{
				    		case in_array($error_code, array('AO07')):
				    			?>
				    			<div class="alert alert-success">
				    				<i class="fa fa-check m-r-xs"></i>
				    				<strong><?php echo @$titles[$error_code]; ?></strong>
				    				<?php echo @$bodies[$error_code]?>
				    			</div>
				    			<?php
				    			break;
				    		case in_array($error_code, array('')):
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
                    <form id="frmUpdateOptions" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="options_update" value="1" />
                        <input type="hidden" name="tab" value="7" />
                        <input type="hidden" name="next_action" value="pjActionContent" />

						<div class="row">
                            <div class="col-lg-11">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-4 control-label"><?php __('lblImage') ?></label>

                                    <div class="col-lg-9 col-md-8 mce-md">
                                        <div class="clearfix">
                                    		<div class="fileinput fileinput-new" data-provides="fileinput">
		                                        <span class="btn btn-primary btn-outline btn-file"><span class="fileinput-new"><i class="fa fa-upload"></i> <?php __('lblSelectImage');?></span>
		                                        <span class="fileinput-exists"><?php __('lblChangeImage');?></span><input name="content_img" type="file"></span>
		                                        <span class="fileinput-filename"></span>
		
		                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">×</a>
		                                    </div>
		                                </div>
		                                <?php 
										if (!empty($tpl['option_arr']['o_image_path']) && is_file(PJ_INSTALL_PATH . $tpl['option_arr']['o_image_path']))
										{
											?>
											<div id="boxContentImage" class="form-group">
												<img src="<?php echo PJ_INSTALL_URL . $tpl['option_arr']['o_image_path']; ?>?r=<?php echo rand(1,9999); ?>" alt="" class="align_middle" style="max-width: 180px; margin-right: 10px;">
												<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionDeleteContentImage" class="btn btn-xs btn-danger btn-outline btnDeleteContentImage"><i class="fa fa-trash"></i> <?php __('btnDelete'); ?></a>
											</div>
											<?php
										} 
										?>
                                    </div>
                                </div>
                            </div><!-- /.col-lg-8 -->
                        </div><!-- /.row -->
                        
                        <div class="row">
                            <div class="col-lg-11">
                                <div class="form-group">
                                    <label class="col-lg-3 col-md-4 control-label"><?php __('lblContent') ?></label>

                                    <div class="col-lg-9 col-md-8">
                                        <?php
                                        foreach ($tpl['lp_arr'] as $v)
                                        {
                                            ?>
                                            <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group ' : NULL;?>pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : 'table'; ?>">
                                                <textarea name="i18n[<?php echo $v['id']; ?>][o_content]" class="form-control mceEditor" style="height: 260px;"><?php echo htmlspecialchars(stripslashes(@$tpl['arr']['i18n'][$v['id']]['o_content'])); ?></textarea>
                                                <?php if ($tpl['is_flag_ready']) : ?>
                                                <span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div><!-- /.col-lg-8 -->
                        </div><!-- /.row -->

                        <div class="hr-line-dashed"></div>

                        <div class="row">
                        	<div class="col-lg-11">
                        		<div class="row">
                        			<div class="col-lg-9 col-lg-offset-3 col-md-8 col-md-offset-4">
			                            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader" data-style="zoom-in">
			                                <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
			                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
			                            </button>
									</div>
	                            </div>
                            </div>
                        </div><!-- /.clearfix -->
                    </form>
                </div>
            </div>
        </div><!-- /.col-lg-12 -->
        <?php
	}
	?>
</div>
<script type="text/javascript">
<?php if ($tpl['is_flag_ready']) : ?>
	var pjCmsLocale = pjCmsLocale || {};
	pjCmsLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjCmsLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
<?php endif; ?>
var myLabel = myLabel || {};
myLabel.alert_del_content_image_title = <?php x__encode('lblDeleteContentImageTitle'); ?>;
myLabel.alert_del_content_image_text = <?php x__encode('lblDeleteContentImageConfirm'); ?>;
myLabel.btn_delete = <?php x__encode('btnDelete'); ?>;
myLabel.btn_cancel = <?php x__encode('btnCancel'); ?>;
</script>