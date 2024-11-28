<?php
if(count($tpl['ticket_arr']) > 0)
{
	?>
	<div class="form-group">
		<label class="control-label"><?php __('lblTicket');?>:</label>
	
		<select name="source_ticket_id" id="source_ticket_id" class="form-control form-control-lg">
			<?php
			foreach ($tpl['ticket_arr'] as $v)
			{
				?><option value="<?php echo $v['id']; ?>"><?php echo pjSanitize::html($v['title']); ?></option><?php
			}
			?>
		</select>
	</div>
	<?php
}
?>