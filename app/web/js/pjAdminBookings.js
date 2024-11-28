var jQuery = jQuery || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var 
			$frmCreateBooking = $('#frmCreateBooking'),
			$frmUpdateBooking = $('#frmUpdateBooking'),
			datepicker = ($.fn.datepicker !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			validate = ($.fn.validate !== undefined),
			select2 = ($.fn.select2 !== undefined),
			reselect = null,
			return_reselect = null,
			validator;
	
		if($(".touchspin3").length > 0)
		{
			$(".touchspin3").TouchSpin({
				min: 0,
				max: 4294967295,
				step: 1,
				verticalbuttons: true,
	            buttondown_class: 'btn btn-white',
	            buttonup_class: 'btn btn-white'
	        }).on('touchspin.on.startspin', function () {
	        	
	        });
		}
		if ($(".select-item").length && select2) {
            $(".select-item").select2({
                placeholder: '-- ' + myLabel.choose + ' --',
                allowClear: true
            });
        }
				
		if ($('#datePickerOptions').length) {
        	$.fn.datepicker.dates['en'] = {
        		days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    		    daysMin: $('#datePickerOptions').data('days').split("_"),
    		    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    		    months: $('#datePickerOptions').data('months').split("_"),
    		    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    		    format: $('#datePickerOptions').data('format'),
            	weekStart: parseInt($('#datePickerOptions').data('wstart'), 10),
    		};
        	$('.datepicker').datepicker({
	            autoclose: true
	        }).on('changeDate', function (selected) {
	        	var $input = $(this).find('input'),
        		elementName = $input.attr('name'),
        		$form = $input.closest('form');
	        	if(elementName == 'date_from')
	        	{
	        		var $toElement = $('#date_to').parent(),
	        			date_to_value = $toElement.datepicker("getUTCDate"),
	        			$minDate = new Date(selected.date.valueOf());
	        		if(date_to_value < selected.date)
					{
    					$toElement.find('input').val($input.val());
					}
	        		$toElement.datepicker('setStartDate', $minDate);
	        	}else if(elementName == 'date_to'){
	        		var $fromElement = $("#date_from").parent(),
	        			$toElement = $('#date_to').parent();
	        		if($fromElement.length > 0 && $toElement.length > 0)
        			{
	        			var $maxDate = new Date(selected.date.valueOf()),
        					date_from_value = $fromElement.datepicker("getUTCDate");
        				if(date_from_value > selected.date)
    					{
        					$fromElement.find('input').val($input.val());
    					}
        				$fromElement.datepicker('setEndDate', $maxDate);
        			}
	        	}
	        	if($frmCreateBooking.length > 0 || $frmUpdateBooking.length > 0)
	        	{
	        		if(elementName == 'booking_date')
		        	{
	        			$('.bs-loader').css('display', 'block');
						$.post("index.php?controller=pjAdminBookings&action=pjActionChangeDate", $form.serialize()).done(function (data) {
							var $html = data.split("--LIMITER--");
							$('#busBox').html($html[0]);
							$('#fromBox').html($html[1]);
							$('#toBox').html($html[2]);
							if ($(".select-item").length && select2) {
					            $(".select-item").select2({
					                placeholder: '-- ' + myLabel.choose + ' --',
					                allowClear: true
					            });
					        }
							$('#total').val('');
							$('#selected_seats').val('');
							$('#ticketBox').css('display', 'none');
							$('#seatsBox').css('display', 'none');
							$('#selectSeatsBox').css('display', 'none');
							$('.bs-loader').css('display', 'none');
						});
		        	} else if(elementName == 'return_date'){
		        		getReturnBuses();
		        	}
	        	}
			});
        };
        
		if ($frmCreateBooking.length > 0 || $frmUpdateBooking.length > 0) {
			$.validator.addMethod('assignedSeats',
				    function (value) { 
						if($('#bus_id').find(':selected').attr('data-set') == 'T')
						{
							return true;
						}else{
							var total_tickets = 0;
							$( ".bs-ticket" ).each(function( index ) {
								var qty = parseInt($( this ).val(), 10);
								total_tickets += qty;
							});
							if($("#assigned_seats").select2('data').length != total_tickets)
							{
								return false
							}else{
								return true;
							}
						}
				    }, myLabel.assigned_seats);
			
			$.validator.addMethod('selectedSeats',
				    function (value) { 
						if($('#bus_id').find(':selected').attr('data-set') == 'T')
						{
							var total_tickets = 0,
								selected_seats = Array();
							$( ".bs-ticket" ).each(function( index ) {
								var qty = parseInt($( this ).val(), 10);
								total_tickets += qty;
							});
							selected_seats = $('#selected_seats').val().split("|");
							if(selected_seats.length != total_tickets)
							{
								return false
							}else{
								return true;
							}
						}else{
							return true;
						}
				    }, myLabel.assigned_seats);
				    
			$frmCreateBooking.validate({
				rules: {
					"uuid": {
						remote: "index.php?controller=pjAdminBookings&action=pjActionCheckUniqueId"
					},
					"assigned_seats[]": {
						assignedSeats: true
					},
					"selected_seats": {
						selectedSeats: true
					}
				},
				messages: {
					"uuid":{
						remote: myLabel.duplicatedUniqueID
					}
				},
				errorPlacement: function (error, element) {
					if (element.attr('name') == 'pickup_id' || element.attr('name') == 'return_id') {
						error.insertAfter(element.parent().parent());
					} else {
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var $_id = $(validator.errorList[0].element, this).closest("div.tab-pane").attr("id");
				    	$('.tab-'+$_id).trigger("click");
				    };
				},
			});
			$frmUpdateBooking.validate({
				rules: {
					"uuid": {
						remote: "index.php?controller=pjAdminBookings&action=pjActionCheckUniqueId&id=" + $frmUpdateBooking.find("input[name='id']").val()
					},
					"assigned_seats[]": {
						assignedSeats: true
					},
					"selected_seats": {
						selectedSeats: true
					}
				},
				messages: {
					"uuid":{
						remote: myLabel.duplicatedUniqueID
					}
				},
				errorPlacement: function (error, element) {
					if (element.attr('name') == 'pickup_id' || element.attr('name') == 'return_id') {
						error.insertAfter(element.parent().parent());
					} else {
						error.insertAfter(element.parent());
					}
				},
				onkeyup: false,
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var $_id = $(validator.errorList[0].element, this).closest("div.tab-pane").attr("id");
				    	$('.tab-'+$_id).trigger("click");
				    };
				}
			});
			
			if($frmCreateBooking.length > 0)
			{
				setBookingRoute();
			}
		}
		
		if ($("#grid").length > 0 && datagrid) {
			function formatStatus(val, obj) {
				if(val == 'confirmed')
				{
					return '<div class="btn bg-confirmed btn-xs no-margin"><i class="fa fa-check"></i> ' + myLabel.confirmed + '</div>';
				}else if(val == 'cancelled'){
					return '<div class="btn bg-cancelled btn-xs no-margin"><i class="fa fa-times"></i> ' + myLabel.cancelled + '</div>';
				}else if(val == 'pending'){
					return '<div class="btn bg-pending btn-xs no-margin"><i class="fa fa-exclamation-triangle"></i> ' + myLabel.pending + '</div>';
				}
			}
			
			var $buttons = [];
			var $actions = [];
			var $editable = false;
			var $select = false;

			if (pjGrid.hasUpdate)
			{
				$editable = true;
				$buttons.push({type: "edit", url: "index.php?controller=pjAdminBookings&action=pjActionUpdate&id={:id}"});
			}			
			if (pjGrid.hasDeleteSingle)
			{
				$buttons.push({type: "delete", url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBooking&id={:id}"});
			}
			if (pjGrid.hasDeleteMulti) 
			{
				$actions.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBookingBulk", render: true, confirmation: myLabel.delete_confirmation});
			}
			if (pjGrid.hasExport) 
			{
				$actions.push({text: myLabel.exportSelected, url: "index.php?controller=pjAdminBookings&action=pjActionExportBooking", ajax: false});
			}
			if ($actions.length > 0) {
				$select = {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				};
			}
			var $grid = $("#grid").datagrid({
				buttons: $buttons,
				columns: [
				          {text: myLabel.client, type: "text", sortable: false, width:140},
				          {text: myLabel.date_time, type: "text", sortable: false, editable: false, width:130},
				          {text: myLabel.bus_route, type: "text", sortable: false, editable: false},
				          {text: myLabel.status, type: "text", sortable: true, editable: false, renderer: formatStatus}],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString,
				dataType: "json",
				fields: ['client', 'date_time', 'route_details', 'status'],
				paginator: {
					actions: $actions,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBookings&action=pjActionSaveBooking&id={:id}",
				select: $select
			});
		}
				
		$(document).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val(),
				status: $this.find("option:selected", "select[name='status']").val(),
				date_from: "",
				date_to: "",
				route_id: "",
				bus_id: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", content.column, content.direction, content.page, content.rowCount);
			return false;
		}).on("change", "#filter_status", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$('.frm-filter').trigger('submit');
			return false;
		}).on("submit", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var obj = {},
				$this = $(this),
				arr = $this.serializeArray(),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				obj[arr[i].name] = arr[i].value;
			}
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking", content.column, content.direction, content.page, content.rowCount);
			return false;
		}).on("reset", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $frm = $('.frm-filter-advanced');
			$frm.find("input[name='date_from']").val('');
			$frm.find("input[name='date_to']").val('');
			$frm.find("select[name='route_id']").val('');
			$frm.find("select[name='bus_id']").val('');			
			$(".btn-advance-search").trigger("click");
			$('.frm-filter-advanced').submit();
			return false;
		}).on("change", "#payment_method", function (e) {
			switch ($("option:selected", this).val()) {
				case 'creditcard':
					$(".boxCC").show();
					break;
				default:
					$(".boxCC").hide();
			}
		}).on("change", "#pickup_id", function (e) {
			var $this = $(this);
			$('.bs-loader').css('display', 'block');
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetLocations&pickup_id=" + $this.val()).done(function (data) {
				$('#toBox').html(data);
				if ($(".select-item").length && select2) {
		            $(".select-item").select2({
		                placeholder: '-- ' + myLabel.choose + ' --',
		                allowClear: true
		            });
		        }
				if ($this.val() != '') {
					$this.valid();
				}
				$('.bs-loader').css('display', 'none');
			});
		}).on("change", "#return_id", function (e) {
			if ($(this).val() != '') {
				$(this).valid();
			}
			if($('#pickup_id').val() == '')
			{
				$('.bs-loader').css('display', 'block');
				$.get("index.php?controller=pjAdminBookings&action=pjActionGetLocations&return_id=" + $(this).val()).done(function (data) {
					$('#fromBox').html(data);
					if ($(".select-item").length && select2) {
			            $(".select-item").select2({
			                placeholder: '-- ' + myLabel.choose + ' --',
			                allowClear: true
			            });
			        }
					$('.bs-loader').css('display', 'none');
				});
			}else{
				getBuses();
			}
		}).on("change", "#bus_id", function (e) {
			var frm = null;
			if($('#bus_id'). val() != '')
			{
				if($frmCreateBooking.length > 0)
				{
					frm = $frmCreateBooking;
				}
				if($frmUpdateBooking.length > 0)
				{
					frm = $frmUpdateBooking;
				}
				$('.bs-loader').css('display', 'block');
				$.post("index.php?controller=pjAdminBookings&action=pjActionGetTickets", frm.serialize()).done(function (data) {
					$('#ticketBox').html(data.ticket);
					$('#bsDepartureTime').html(data.departure_time);
					$('#bsArrivalTime').html(data.arrival_time);
					$('#ticketBox').css('display', 'block');
					clearPrice();
					$('#selected_seats').val('');
					$('#bs_selected_seat_label').html('');
					$('#seatsBox').css('display', 'none');
					$('#selectSeatsBox').css('display', 'none');
					$('.bs-loader').css('display', 'none');
				
					setBookingRoute();
				});
			}
		}).on("change", "#return_bus_id", function (e) {
			var frm = null;
			if($(this). val() != '')
			{
				if($frmCreateBooking.length > 0)
				{
					frm = $frmCreateBooking;
				}
				if($frmUpdateBooking.length > 0)
				{
					frm = $frmUpdateBooking;
				}
				$('.bs-loader').css('display', 'block');
				$.post("index.php?controller=pjAdminBookings&action=pjActionGetReturnTickets", frm.serialize()).done(function (data) {
					$('#returnTicketBox').html(data.ticket);
					$('#returnTicketBox').css('display', 'block');
					clearPrice();
					$('#return_selected_seats').val('');
					$('#bs_return_selected_seat_label').html('');
					$('#seatsReturnBox').css('display', 'none');
					$('#selectReturnSeatsBox').css('display', 'none');
					$('.bs-loader').css('display', 'none');
				
					setBookingReturnRoute();
				});
			}
		}).on("click", "input:checkbox[name=is_return]", function (e) {
			$(".returnBox").hide();
			if (this.checked && $('#bus_id'). val() != '') 
			{
				$(".returnBox").show();
				$('#return_selected_seats').addClass('required');
			}else{
				$('#return_selected_seats').removeClass('required');
			}			
		}).on("change", ".bs-ticket", function (e) {
			var sub_total = 0,
				tax = 0,
				total, 
				deposit = 0,
				total_tickets = 0,
				$this = $(this),
				number_of_seats = parseInt($('#bs_number_of_seats').val(), 10),
				max_seats = 0;
			$( ".bs-ticket" ).each(function( index ) {
				var qty = parseInt($( this ).val(), 10),
					price = parseFloat($(this).attr('data-price'));
				sub_total += qty * price;
				total_tickets += qty;
			});
			$( ".bs-return-ticket" ).each(function( index ) {
				var qty = parseInt($( this ).val(), 10),
					price = parseFloat($(this).attr('data-price'));
				sub_total += qty * price;
			});
			max_seats = number_of_seats - total_tickets;
			reCalculatingTickets($this, max_seats);
			if(sub_total > 0)
			{
				tax = (sub_total * parseFloat($('#tax').attr('data-tax'))) / 100;
				total = sub_total + tax;
				deposit = (total * parseFloat($('#deposit').attr('data-deposit'))) / 100;
				$('#sub_total').val(sub_total.toFixed(2));
				$('#tax').val(tax.toFixed(2));
				$('#total').val(total.toFixed(2));
				$('#deposit').val(deposit.toFixed(2));
				setPickupPrice();
				setReturnPrice();
				if($('#bus_id').find(':selected').attr('data-set') == 'T')
				{
					$('#seatsBox').css('display', 'block');
					$('#selectSeatsBox').css('display', 'none');
					$('#selected_seats').addClass('required');
				}else{
					$('#selected_seats').removeClass('required');
					var frm = null;
					if($frmCreateBooking.length > 0)
					{
						frm = $frmCreateBooking;
					}
					if($frmUpdateBooking.length > 0)
					{
						frm = $frmUpdateBooking;
					}
					$('.bs-loader').css('display', 'block');
					$.post("index.php?controller=pjAdminBookings&action=pjActionGetSeats", frm.serialize()).done(function (data) {
						$('#selectSeatsBox').html(data);
						if ($(".select-item").length && select2) {
				            $(".select-item").select2({
				                placeholder: '-- ' + myLabel.choose + ' --',
				                allowClear: true
				            });
				        }
						$('#seatsBox').css('display', 'none');
						$('#selectSeatsBox').css('display', 'block');
						$('.bs-loader').css('display', 'none');
					});
				}
			}else{
				clearPrice();
				$('#seatsBox').css('display', 'none');
			}
		}).on("change", ".bs-return-ticket", function (e) {
			var sub_total = 0,
				tax = 0,
				total, 
				deposit = 0,
				total_tickets = 0,
				$this = $(this),
				number_of_seats = parseInt($('#bs_return_number_of_seats').val(), 10),
				max_seats = 0;
			$( ".bs-ticket" ).each(function( index ) {
				var qty = parseInt($( this ).val(), 10),
					price = parseFloat($(this).attr('data-price'));
				sub_total += qty * price;
				total_tickets += qty;
			});
			$( ".bs-return-ticket" ).each(function( index ) {
				var qty = parseInt($( this ).val(), 10),
					price = parseFloat($(this).attr('data-price'));
				sub_total += qty * price;
			});
			
			max_seats = number_of_seats - total_tickets;
			reCalculatingReturnTickets($this, max_seats);
			
			if(sub_total > 0)
			{
				tax = (sub_total * parseFloat($('#tax').attr('data-tax'))) / 100;
				total = sub_total + tax;
				deposit = (total * parseFloat($('#deposit').attr('data-deposit'))) / 100;
				$('#sub_total').val(sub_total.toFixed(2));
				$('#tax').val(tax.toFixed(2));
				$('#total').val(total.toFixed(2));
				$('#deposit').val(deposit.toFixed(2));
				setPickupPrice();
				setReturnPrice();
				if($('#return_bus_id').find(':selected').attr('data-set') == 'T')
				{
					$('#seatsReturnBox').css('display', 'block');
					$('#selectReturnSeatsBox').css('display', 'none');
					$('#return_selected_seats').addClass('required');
				}else{
					$('#return_selected_seats').removeClass('required');
					
					var frm = null;
					if($frmCreateBooking.length > 0)
					{
						frm = $frmCreateBooking;
					}
					if($frmUpdateBooking.length > 0)
					{
						frm = $frmUpdateBooking;
					}
					$('.bs-loader').css('display', 'block');
					$.post("index.php?controller=pjAdminBookings&action=pjActionGetReturnSeats", frm.serialize()).done(function (data) {
						$('#selectReturnSeatsBox').html(data);
						if ($(".select-item").length && select2) {
				            $(".select-item").select2({
				                placeholder: '-- ' + myLabel.choose + ' --',
				                allowClear: true
				            });
				        }
						$('#seatsReturnBox').css('display', 'none');
						$('#selectReturnSeatsBox').css('display', 'block');
						$('.bs-loader').css('display', 'none');
						
						setBookingReturnRoute();
					});
				}
			}else{
				clearPrice();
				$('#seatsBox').css('display', 'none');
			}
		}).on("click", ".bs-select-seats", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $selectSeatContentWrapper = $('#selectSeatContentWrapper'),
				$frm = null;
			if ($frmCreateBooking.length > 0) 
			{
				$frm = $frmCreateBooking;
			}
			if ($frmUpdateBooking.length > 0) 
			{
				$frm = $frmUpdateBooking;
			}	
			$.post("index.php?controller=pjAdminBookings&action=pjActionGetSeats", $frm.serialize()).done(function (data) {
				$selectSeatContentWrapper.html(data);
				reselect = null;
			});
			$('#selectSeatModal').modal('show');
			return false;
		}).on("click", ".bs-select-return-seats", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $returnSelectSeatContentWrapper = $('#returnSelectSeatContentWrapper'),
				$frm = null;
			if ($frmCreateBooking.length > 0) 
			{
				$frm = $frmCreateBooking;
			}
			if ($frmUpdateBooking.length > 0) 
			{
				$frm = $frmUpdateBooking;
			}	
			$.post("index.php?controller=pjAdminBookings&action=pjActionGetReturnSeats", $frm.serialize()).done(function (data) {
				$returnSelectSeatContentWrapper.html(data);
				return_reselect = null;
			});
			$('#returnSelectSeatModal').modal('show');
			return false;
		}).on("click", ".bs-available", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var seat_id = $(this).attr('data-id'),
				seat_name = $(this).attr('data-name'),
				seat_arr = getSeatsArray(),
				seat_name_arr = getSeatsNameArray(),
				quantity = 0;
			$( ".bs-ticket" ).each(function( index ) {
				quantity += parseInt($( this ).val(), 10);
			});
			if(quantity > seat_arr.length && jQuery.inArray( seat_id, seat_arr ) == -1)
			{
				$(this).addClass('bs-selected');
				seat_arr.push(seat_id);
				$('#selected_seats').val(seat_arr.join("|"));
				seat_name_arr.push(seat_name);
				$('#bs_selected_seat_label').html(seat_name_arr.join(", "));
				$('#reload_map').val(0);
			}
		}).on("click", ".bs-return-available", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			
			var seat_id = $(this).attr('data-id'),
				seat_name = $(this).attr('data-name'),
				seat_arr = getReturnSeatsArray(),
				seat_name_arr = getReturnSeatsNameArray(),
				quantity = 0;
			$( ".bs-return-ticket" ).each(function( index ) {
				quantity += parseInt($( this ).val(), 10);
			});
			if(quantity > seat_arr.length && jQuery.inArray( seat_id, seat_arr ) == -1)
			{
				$(this).addClass('bs-return-selected');
				seat_arr.push(seat_id);
				$('#return_selected_seats').val(seat_arr.join("|"));
				seat_name_arr.push(seat_name);
				$('#bs_return_selected_seat_label').html(seat_name_arr.join(", "));
				$('#return_reload_map').val(0);
			}
		}).on("change", "#pickup_id", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}			
			$("#return_id option").attr("disabled",false);
		    var source = $(this).find("option:selected").val();
		    $("#return_id option[value='"+source+"']").attr("disabled",true);
		    $('#return_id').val('');
		    $('#return_id').trigger("liszt:updated").valid();
		}).on("change", ".number", function (e) {
			var v = parseFloat(this.value);
		    if (isNaN(v)) {
		        this.value = '';
		    } else {
		        this.value = v.toFixed(2);
		    }
		    if (parseFloat(this.value) >= 99999999999999.99) {
		    	this.value = '99999999999999.99';
		    }
		}).on("click", "#btnReselectSeat", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}			
			$('#selected_seats').val('');
			$('#bs_selected_seat_label').html('');
			$('#reload_map').val(0);
			reselect = null;
			$(".bs-selected").each(function( index ) {
				$(this).removeClass('bs-selected');
			});
		}).on("click", "#btnSelectSeatConfirm", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}			
			$('#selectSeatModal').modal('hide');
		}).on("click", "#btnReturnReselectSeat", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}			
			$('#return_selected_seats').val('');
			$('#bs_return_selected_seat_label').html('');
			$('#return_reload_map').val(0);
			return_reselect = null;
			$(".bs-return-selected").each(function( index ) {
				$(this).removeClass('bs-return-selected');
			});
		}).on("click", "#btnReturnSelectSeatConfirm", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}			
			$('#returnSelectSeatModal').modal('hide');
		}).on("change", "#status", function (e) {
			var $pjBsSummaryWrapper = $('#pjBsSummaryWrapper');
			var value = $("#status option:selected").val();
			var text = $("#status option:selected").text();
			var bg_class = 'bg-' + value;
			$pjBsSummaryWrapper.find('.panel-heading').removeClass("bg-pending").removeClass("bg-cancelled").removeClass("bg-confirmed").addClass(bg_class);
			$pjBsSummaryWrapper.find('.status-text').html(text);
		}).on("click", ".widget-client-info", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$('.tab-client-details').trigger('click');
			return false;
		}).on("click", ".confirmation-email", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var booking_id = $(this).attr('data-id');
			var document_id = 0;
			var $confirmEmailContentWrapper = $('#confirmEmailContentWrapper');
			
			$('#btnSendEmailConfirm').attr('data-booking_id', booking_id);
			
			$confirmEmailContentWrapper.html("");
			$.get("index.php?controller=pjAdminBookings&action=pjActionEmailConfirmation", {
				"booking_id": booking_id
			}).done(function (data) {
				$confirmEmailContentWrapper.html(data);
				if(data.indexOf("pjResendAlert") == -1)
				{
					if ($('#mceEditor').length > 0) {
						myTinyMceDestroy.call(null);
						myTinyMceInit.call(null, 'textarea#mceEditor');
			        }
					
					validator = $confirmEmailContentWrapper.find("form").validate({});
					$('#btnSendEmailConfirm').show();
				}else{
					$('#btnSendEmailConfirm').hide();
				}	
				$('#confirmEmailModal').modal('show');
			});
			return false;
		}).on("click", "#btnSendEmailConfirm", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var $confirmEmailContentWrapper = $('#confirmEmailContentWrapper');
			if (validator.form()) {
				$('#mceEditor').html( tinymce.get('mceEditor').getContent() );
				$(this).attr("disabled", true);
				var l = Ladda.create(this);
			 	l.start();
				$.post("index.php?controller=pjAdminBookings&action=pjActionEmailConfirmation", $confirmEmailContentWrapper.find("form").serialize()).done(function (data) {
					if (data.status == "OK") {
						$('#confirmEmailModal').modal('hide');
					} else {
						$('#confirmEmailModal').modal('hide');
					}
					$this.attr("disabled", false);
					l.stop();
				});
			}
			return false;
		}).on("click", ".cancellation-email", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var booking_id = $(this).attr('data-id');
			var document_id = 0;
			var $cancellationEmailContentWrapper = $('#cancellationEmailContentWrapper');
			
			$('#btnSendEmailCancellation').attr('data-booking_id', booking_id);
			
			$cancellationEmailContentWrapper.html("");
			$.get("index.php?controller=pjAdminBookings&action=pjActionEmailCancellation", {
				"booking_id": booking_id
			}).done(function (data) {
				$cancellationEmailContentWrapper.html(data);
				if(data.indexOf("pjResendAlert") == -1)
				{
					if ($('#mceEditor').length > 0) {
						myTinyMceDestroy.call(null);
						myTinyMceInit.call(null, 'textarea#mceEditor');
			        }
					validator = $cancellationEmailContentWrapper.find("form").validate({});
					$('#btnSendEmailCancellation').show();
				}else{
					$('#btnSendEmailCancellation').hide();
				}	
				$('#cancellationEmailModal').modal('show');
			});
			return false;
		}).on("click", "#btnSendEmailCancellation", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var $cancellationEmailContentWrapper = $('#cancellationEmailContentWrapper');
			if (validator.form()) {
				$('#mceEditor').html( tinymce.get('mceEditor').getContent() );
				$(this).attr("disabled", true);
				var l = Ladda.create(this);
			 	l.start();
				$.post("index.php?controller=pjAdminBookings&action=pjActionEmailCancellation", $cancellationEmailContentWrapper.find("form").serialize()).done(function (data) {
					if (data.status == "OK") {
						$('#cancellationEmailModal').modal('hide');
					} else {
						$('#cancellationEmailModal').modal('hide');
					}
					$this.attr("disabled", false);
					l.stop();
				});
			}
			return false;
		}).on("click", ".payment-email", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var booking_id = $(this).attr('data-id');
			var document_id = 0;
			var $paymentEmailContentWrapper = $('#paymentEmailContentWrapper');
			
			$('#btnSendEmailPayment').attr('data-booking_id', booking_id);
			
			$paymentEmailContentWrapper.html("");
			$.get("index.php?controller=pjAdminBookings&action=pjActionEmailPayment", {
				"booking_id": booking_id
			}).done(function (data) {
				$paymentEmailContentWrapper.html(data);
				if(data.indexOf("pjResendAlert") == -1)
				{
					if ($('#mceEditor').length > 0) {
						myTinyMceDestroy.call(null);
						myTinyMceInit.call(null, 'textarea#mceEditor');
			        }
					validator = $paymentEmailContentWrapper.find("form").validate({});
					$('#btnSendEmailPayment').show();
				}else{
					$('#btnSendEmailPayment').hide();
				}	
				$('#paymentEmailModal').modal('show');
			});
			return false;
		}).on("click", "#btnSendEmailPayment", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var $paymentEmailContentWrapper = $('#paymentEmailContentWrapper');
			if (validator.form()) {
				$('#mceEditor').html( tinymce.get('mceEditor').getContent() );
				$(this).attr("disabled", true);
				var l = Ladda.create(this);
			 	l.start();
				$.post("index.php?controller=pjAdminBookings&action=pjActionEmailPayment", $paymentEmailContentWrapper.find("form").serialize()).done(function (data) {
					if (data.status == "OK") {
						$('#paymentEmailModal').modal('hide');
					} else {
						$('#paymentEmailModal').modal('hide');
					}
					$this.attr("disabled", false);
					l.stop();
				});
			}
			return false;
		});
		
		function setPickupPrice()
		{
			var sub_total = 0,
				tax = 0,
				total, 
				deposit = 0;
			$( ".bs-ticket" ).each(function( index ) {
				var qty = parseInt($( this ).val(), 10),
					price = parseFloat($(this).attr('data-price'));
				sub_total += qty * price;
			});
			tax = (sub_total * parseFloat($('#tax').attr('data-tax'))) / 100;
			total = sub_total + tax;
			deposit = (total * parseFloat($('#deposit').attr('data-deposit'))) / 100;
			$('#pickup_sub_total').val(sub_total.toFixed(2));
			$('#pickup_tax').val(tax.toFixed(2));
			$('#pickup_total').val(total.toFixed(2));
			$('#pickup_deposit').val(deposit.toFixed(2));
		}
		function setReturnPrice()
		{
			var sub_total = 0,
				tax = 0,
				total, 
				deposit = 0;
			$( ".bs-return-ticket" ).each(function( index ) {
				var qty = parseInt($( this ).val(), 10),
					price = parseFloat($(this).attr('data-price'));
				sub_total += qty * price;
			});
			tax = (sub_total * parseFloat($('#tax').attr('data-tax'))) / 100;
			total = sub_total + tax;
			deposit = (total * parseFloat($('#deposit').attr('data-deposit'))) / 100;
			$('#return_sub_total').val(sub_total.toFixed(2));
			$('#return_tax').val(tax.toFixed(2));
			$('#return_total').val(total.toFixed(2));
			$('#return_deposit').val(deposit.toFixed(2));
		}
		function clearPrice()
		{
			$('#sub_total').val('');
			$('#tax').val('');
			$('#total').val('');
			$('#deposit').val('');
			$('#pickup_sub_total').val('');
			$('#pickup_tax').val('');
			$('#pickup_total').val('');
			$('#pickup_deposit').val('');
			$('#return_sub_total').val('');
			$('#return_tax').val('');
			$('#return_total').val('');
			$('#return_deposit').val('');
		}
		function getSeatsArray()
		{
			var selected_seats = $('#selected_seats').val(),
				seat_arr = Array();
			if(selected_seats != '')
			{
				seat_arr = selected_seats.split("|");
			}
			return seat_arr;
		}
		function getReturnSeatsArray()
		{
			var selected_seats = $('#return_selected_seats').val(),
				seat_arr = Array();
			if(selected_seats != '')
			{
				seat_arr = selected_seats.split("|");
			}
			return seat_arr;
		}
		function getSeatsNameArray()
		{
			var selected_seats = $('#bs_selected_seat_label').html(),
				seat_arr = Array();
			if(selected_seats != '')
			{
				seat_arr = selected_seats.split(", ");
			}
			return seat_arr;
		}
		function getReturnSeatsNameArray()
		{
			var selected_seats = $('#bs_return_selected_seat_label').html(),
				seat_arr = Array();
			if(selected_seats != '')
			{
				seat_arr = selected_seats.split(", ");
			}
			return seat_arr;
		}
		function setBookingRoute()
		{
			var booking_route = '';
			if($('#bus_id').val() != '' && $('#pickup_id').val() != '' && $('#return_id').val() != '')
			{
				booking_route += $('#bus_id option:selected').text() + '<br/>';
				booking_route += myLabel.from + ' ' + $('#pickup_id option:selected').text() + ' ';
				booking_route += myLabel.to + ' ' + $('#return_id option:selected').text();
			}
			$('#booking_route').val(booking_route);
		}
		function setBookingReturnRoute()
		{
			var booking_route = '';
			if($('#return_bus_id').val() != '' && $('#pickup_id').val() != '' && $('#return_id').val() != '')
			{
				booking_route += $('#return_bus_id option:selected').text() + '<br/>';
				booking_route += myLabel.from + ' ' + $('#return_id option:selected').text();
				booking_route += myLabel.to + ' ' + $('#pickup_id option:selected').text() + ' ';
			}
			$('#booking_return_route').val(booking_route);
		}
		function reCalculatingTickets($this, max_seats)
		{
			var current_value = parseInt($this.val(), 10),
			number_of_seats = parseInt($('#bs_number_of_seats').val(), 10);

			$('.bs-ticket').each(function( index ) {
				
				if($this.attr('name') != $(this).attr('name'))
				{
					var selected_value = parseInt($(this).val(), 10),
						new_options = {},
						$that = $(this);
					$that.empty();
					if(selected_value > 0)
					{
						max_seats = (number_of_seats - current_value);
					}
					for(var i = 0; i <= max_seats; i++)
					{
						new_options[i] = i;
					}
					$.each(new_options, function(key, value) {
						$that.append($("<option></option>").attr("value", value).text(key));
					});
					$that.val(selected_value);
				}
			});
		}
		function reCalculatingReturnTickets($this, max_seats)
		{
			var current_value = parseInt($this.val(), 10),
			number_of_seats = parseInt($('#bs_return_number_of_seats').val(), 10);

			$('.bs-return-ticket').each(function( index ) {
				
				if($this.attr('name') != $(this).attr('name'))
				{
					var selected_value = parseInt($(this).val(), 10),
						new_options = {},
						$that = $(this);
					$that.empty();
					if(selected_value > 0)
					{
						max_seats = (number_of_seats - current_value);
					}
					for(var i = 0; i <= max_seats; i++)
					{
						new_options[i] = i;
					}
					$.each(new_options, function(key, value) {
						$that.append($("<option></option>").attr("value", value).text(key));
					});
					$that.val(selected_value);
				}
			});
		}
		function getBuses()
		{
			if($('#pickup_id'). val() != '' && $('#return_id'). val() != '')
			{
				var frm = null;
				if($frmCreateBooking.length > 0)
				{
					frm = $frmCreateBooking;
				}
				if($frmUpdateBooking.length > 0)
				{
					frm = $frmUpdateBooking;
				}
				$('.bs-loader').css('display', 'block');
				$.post("index.php?controller=pjAdminBookings&action=pjActionGetBuses", frm.serialize()).done(function (data) {
					$('#busBox').html(data);
					$('.bs-loader').css('display', 'none');
					if ($(".select-item").length && select2) {
			            $(".select-item").select2({
			                placeholder: '-- ' + myLabel.choose + ' --',
			                allowClear: true
			            });
			        }
					clearPrice();
					$('#selected_seats').val('');
					$('#bs_selected_seat_label').html('');
					$('#ticketBox').css('display', 'none');
					$('#seatsBox').css('display', 'none');
					$('#selectSeatsBox').css('display', 'none');
					
					setBookingRoute();
				});
			}
		}
		function getReturnBuses()
		{
			if($('#pickup_id'). val() != '' && $('#return_id'). val() != '')
			{
				var frm = null;
				if($frmCreateBooking.length > 0)
				{
					frm = $frmCreateBooking;
				}
				if($frmUpdateBooking.length > 0)
				{
					frm = $frmUpdateBooking;
				}

				$.post("index.php?controller=pjAdminBookings&action=pjActionGetReturnBuses", frm.serialize()).done(function (data) {
					$('#returnBox').html(data);
					if ($(".select-item").length && select2) {
			            $(".select-item").select2({
			                placeholder: '-- ' + myLabel.choose + ' --',
			                allowClear: true
			            });
			        }
					setBookingReturnRoute();
				});
			}
		}
		
		function myTinyMceDestroy() {
			if (window.tinymce === undefined) {
				return;
			}
			
			var iCnt = tinymce.editors.length;
			
			if (!iCnt) {
				return;
			}
			
			for (var i = 0; i < iCnt; i++) {
				tinymce.remove(tinymce.editors[i]);
			}
		}
		
		function myTinyMceInit(pSelector) {			
			if (window.tinymce === undefined) {
				return;
			}
			
			tinymce.init({
				relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
				browser_spellcheck : true,
			    contextmenu: false,
			    selector: pSelector,
			    theme: "modern",
			    height: 480,
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
			    ],
			    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
			    image_advtab: true,
			    menubar: "file edit insert view table tools",
			    setup: function (editor) {
			    	editor.on('change', function (e) {
			    		editor.editorManager.triggerSave();
			    	});
			    }
			});
		}

		if ($('.mceEditor').length > 0) {
			myTinyMceDestroy.call(null);
			myTinyMceInit.call(null, 'textarea.mceEditor');
        }
	});
})(jQuery);