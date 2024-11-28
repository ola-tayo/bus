<?php 
$validate = str_replace(array('"', "'"), array('\"', "\'"), __('validate', true, true));
$index = $controller->_get->toString('index');
?>
<div class="container-fluid pjBsDriverWrap">
	<?php include_once PJ_VIEWS_PATH . 'pjFrontEnd/elements/driver_header.php';?>
	<div class="panel panel-default pjBsMain">
		<div class="panel-body pjBsBody">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-sm-12">
					<form id="pjBsDriverProfileForm_<?php echo $index;?>" class="pjBsFormCheckOut" action="#" method="post">
						<input type="hidden" name="id" value="<?php echo $controller->getUserId();?>" />
						<input type="hidden" name="update_profile" value="1" />
						<div class="form-horizontal">
							<div class="form-group">
								<h2 class="col-xs-12 pjBsFormTitle text-center"><?php __('front_update_profile');?></h2>
							</div><!-- /.form-group -->
							
							<div class="form-group required">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_email'); ?> <span class="pjBsAsterisk"></span>: </label><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label -->
						
								<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
									<input type="text" name="email" id="email" value="<?php echo pjSanitize::html($tpl['arr']['email']);?>" class="form-control email required" data-msg-required="<?php echo $validate['cus_email'];?>" data-msg-email="<?php echo $validate['cus_email_invalid'];?>" data-msg-remote="<?php echo $validate['cus_email_taken'];?>" />
						
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
								</div><!-- /.col-lg-9 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
							
							<div class="form-group required">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_password'); ?></label><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label -->
						
								<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
									<input type="password" name="password" value="<?php echo pjSanitize::html($tpl['arr']['password']);?>" class="form-control required" data-msg-required="<?php echo $validate['cus_password'];?>" />
						
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
								</div><!-- /.col-lg-9 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
							
							<div class="form-group required">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_name'); ?></label><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label -->
								<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
									<input type="text" name="name" value="<?php echo pjSanitize::html($tpl['arr']['name']);?>" class="form-control required" data-msg-required="<?php echo $validate['cus_name'];?>" />						
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
								</div><!-- /.col-lg-9 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
								
							<div class="form-group">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label"><?php __('front_label_phone'); ?></label><!-- /.col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label -->
								<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
									<input type="text" name="phone" id="phone" value="<?php echo pjSanitize::html($tpl['arr']['phone']);?>" class="form-control" data-msg-required="<?php echo $validate['cus_phone'];?>" />
						
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div><!-- /.help-block with-errors -->
								</div><!-- /.col-lg-9 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								<label class="col-lg-3 col-md-3 col-sm-4 col-xs-12 control-label">&nbsp;</label><!-- /.col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label -->
								<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
									<button type="submit" id="pjBsBtnUpdateDriverProfile_<?php echo $index;?>" class="btn btn-primary"><?php __('front_btn_update');?></button>
								</div><!-- /.col-lg-10 col-md-9 col-sm-8 col-xs-12 -->
							</div><!-- /.form-group -->
						</div>
						<div id="pjBsDriverProfileMsg_<?php echo $index;?>"></div>
					</form>
				</div>
			</div>
		</div>
	</div><!-- panel panel-default pjBsMain -->
</div><!-- /.container-fluid -->