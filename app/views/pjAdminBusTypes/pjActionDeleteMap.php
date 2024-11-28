<?php
if($tpl['code'] == 200)
{ 
	?>
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
	<?php
}else{
	echo $tpl['code'];
} 
?>