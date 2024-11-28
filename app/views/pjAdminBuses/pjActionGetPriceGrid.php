<?php 
$number_of_locations = count($tpl['location_arr']); 
if ($number_of_locations > 0) { ?>
	<div class="bs_price_wrap">
		<table class="tblTicketPricesGrid" id="tblTicketPricesGrid" cellpadding="0" cellspacing="0" style="width: 100%;" border="0">
			 <thead>
      			<tr>
      				<th>&nbsp;</th>
      				<?php foreach($tpl['location_arr'] as $k => $v) { ?>
      					<?php if($k > 0) { ?>
      						<th><?php echo pjSanitize::clean($v['name'])?></th>
      					<?php } ?>
      				<?php } ?>
      			</tr>
      		</thead>
      		<tbody>
      			<?php
				foreach($tpl['location_arr'] as $k => $row)
				{
					if($k <= ($number_of_locations - 2))
					{
						?>
						<tr>
							<td><?php echo pjSanitize::clean($row['name'])?></td>
							<?php
							$j = 1;
							foreach($tpl['location_arr'] as $col)
							{
								if($j > 1)
								{
									$pair_id = $row['city_id'] . '_' . $col['city_id'];
									?>
									<td>
										<?php
										if($col['order'] > $row['order'])
										{ 
											?>
											<div class="input-group">
												<input type="text" class="form-control number" name="price_<?php echo $pair_id;?>" value="<?php echo isset($tpl['price_arr'][$pair_id]) ? $tpl['price_arr'][$pair_id] : null;?>" data-msg-number="<?php __('prices_invalid_price');?>">
												<span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
											</div>
											<?php
										}else{
											echo '&nbsp;';
										} 
										?>
									</td>
									<?php
								}
								$j++;
							} 
							?>
						</tr>
						<?php
					}
				} 
				?>
      		</tbody>
		</table>
	</div>
<?php } ?>