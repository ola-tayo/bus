var jQuery = jQuery || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateBus = $("#frmCreateBus"),
			$frmUpdateTime = $("#frmUpdateTime"),
			$frmNotOperating = $("#frmNotOperating"),
			$frmUpdateTicket = $("#frmUpdateTicket"),
			$frmUpdatePrice = $("#frmUpdatePrice"),
			$frmCopyPrice = $("#frmCopyPrice"),
			multilang = ($.fn.multilang !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			remove_arr = new Array();
		
		if (multilang && 'pjCmsLocale' in window) {
			$(".multilang").multilang({
				langs: pjCmsLocale.langs,
				flagPath: pjCmsLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					
				}
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
        	
        	if ($('.datepick').length > 0) {
            	$('.datepick').datepicker({autoclose: true}).on('changeDate', function (selected) {
            		if($(this).attr('name') == 'start_date')
            		{
            			if($('input[name="end_date"]').length > 0)
            			{
            				var $to = $('input[name="end_date"]');
            				var end_date_value = $to.datepicker("getUTCDate");
            				if(end_date_value < selected.date)
        					{
            					$to.val($('input[name="start_date"]').val());
        					}
            			}
            		}
            		
            		if($(this).attr('name') == 'end_date')
            		{
            			if($('input[name="start_date"]').length > 0)
            			{
            				var $from = $('input[name="start_date"]');
            				var start_date_value = $from.datepicker("getUTCDate");
            				if(start_date_value > selected.date)
        					{
            					$from.val($('input[name="end_date"]').val());
        					}
            			}
            		}
                });
            }
        };
        
		if ($('.pj-timepicker').length) {
        	$( ".pj-timepicker" ).each(function( index ) {
        		var $this = $(this);
        		$this.clockpicker({
                	twelvehour: myLabel.showperiod,
                	autoclose: true,
                	afterDone: function() {
            			
                    }
                });
    		});
        };
		if($('.i-checks').length > 0)
		{
			$('.i-checks').iCheck({
	            checkboxClass: 'icheckbox_square-green',
	            radioClass: 'iradio_square-green'
	        });
		}
		
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
		
		function setTickets()
		{
			var index_arr = new Array();
				
			$('#bs_ticket_list').find(".bs-ticket-row").each(function (index, row) {
				index_arr.push($(row).attr('data-index'));
			});
			$('#index_arr').val(index_arr.join("|"));
		}
		
		if($frmNotOperating.length > 0)
		{
			if($('#bs_date_container').find('.form-group').length > 0)
			{
				$('.pjBrsNoDates').hide();
			}else{
				$('.pjBrsNoDates').show();
			}
			$frmNotOperating.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false
			});
		}
		if ($frmCreateBus.length > 0) {
			$frmCreateBus.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
		
		if ($frmUpdateTime.length > 0) {
			$frmUpdateTime.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false
			});
			$('[data-toggle="tooltip"]').tooltip();
		}
		
		if ($frmUpdateTicket.length > 0) {
			$frmUpdateTicket.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				ignore: "",
				invalidHandler: function (event, validator) {
				    $(".pj-multilang-wrap").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).css('display','block');
						}else{
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId)
						{
							$(this).addClass('pj-form-langbar-item-active');
						}else{
							$(this).removeClass('pj-form-langbar-item-active');
						}
					});
				}
			});
			
			$frmUpdateTicket.submit(function(e){
				var ladda_buttons = $frmUpdateTicket.find('.ladda-button');
			    if(ladda_buttons.length > 0)
                {
                    var l = ladda_buttons.ladda();
                    l.ladda('start');
                }
				var valid = true,
					localeId = null;	
				$("#frmUpdateTicket .fdRequired").each(function() {
					if($(this).val() == '')
					{
						valid = false;
				    	$(this).addClass('pj-error-field');
				    	if(localeId == null)
				    	{
				    		localeId = $(this).attr('lang');
				    	}
				    	
					}else{
						$(this).removeClass('pj-error-field');
					}
					
				});
				if(localeId != null)
				{
					$(".pj-multilang-wrap").each(function( index ) {
						if($(this).attr('data-index') == localeId)
						{
							$(this).css('display','block');
						}else{
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == localeId)
						{
							$(this).addClass('pj-form-langbar-item-active');
						}else{
							$(this).removeClass('pj-form-langbar-item-active');
						}
					});
				}	
				if(valid == true) {
					if($('#set_seats_count').is(":checked"))
					{
						var total_counts = 0,
							seats_available = parseInt($('#seats_available').val(), 10);
						$('#bs_ticket_list').find('.ticket-count').each(function( index ) {
							total_counts += parseInt($(this).val(), 10);
						});
						if(total_counts == seats_available)
						{
							if($frmUpdateTicket.valid())
							{
								setTickets();
							}
						}else{
							swal({
				    			title: myLabel.validate,
								text: myLabel.equalTo,
								type: "warning",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btn_close,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								l.ladda('stop');
								swal.close();
							});
							return false;
						}
					}else{
						if($frmUpdateTicket.valid())
						{
							setTickets();
						}
					}	
				} else {
					l.ladda('stop');
					return false;
				}
			});
		}
		
		if ($frmUpdatePrice.length > 0) {
			$frmUpdatePrice.validate({
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false
			});
			
			if ($('.tblTicketPricesGrid').length > 0) {
				$('.tblTicketPricesGrid').tableHeadFixer({"left" : 1});
			}
		}
				
		if ($("#grid").length > 0 && datagrid) {
			var $buttons = [];
			var $actions = [];
			var $editable = false;
			var $select = false;

			if (pjGrid.hasAccessUpdate)
			{
				$editable = true;
				$buttons.push({type: "edit", url: "index.php?controller=pjAdminBuses&action=pjActionTime&id={:id}"});
			}			
			if (pjGrid.hasAccessDeleteSingle)
			{
				$buttons.push({type: "delete", url: "index.php?controller=pjAdminBuses&action=pjActionDeleteBus&id={:id}"});
			}
			if (pjGrid.hasAccessDeleteMulti) 
			{
				$actions.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminBuses&action=pjActionDeleteBusBulk", render: true, confirmation: myLabel.delete_confirmation});
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
				columns: [{text: myLabel.route, type: "text", sortable: true, editable: false},
				          {text: myLabel.depart_arrive, type: "text", sortable: false, editable: false},
				          {text: myLabel.from_to, type: "text", sortable: true, editable: false}],
				dataUrl: "index.php?controller=pjAdminBuses&action=pjActionGetBus",
				dataType: "json",
				fields: ['route', 'depart_arrive', 'from_to'],
				paginator: {
					actions: $actions,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBuses&action=pjActionSaveBus&id={:id}",
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
				route_id: $('#filter_route_id').val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBuses&action=pjActionGetBus", "route", "ASC", content.page, content.rowCount);
			return false;
		}).on("change", "#filter_route_id", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $('.frm-filter').find("input[name='q']").val(),
				route_id: $this.val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBuses&action=pjActionGetBus", "route", "ASC", content.page, content.rowCount);
			return false;
		}).on("change", "#route_id", function (e) {
			var route_id = $(this).val();
			if(route_id == '')
			{
				$('#bs_bus_locations').html('');
			}else{
				var qs = '';
				if ($frmUpdateTime.length > 0) 
				{
					qs = '&bus_id=' + $('#id').val();
				}
				$('.bs-loader').css('display', 'block');
				$.get("index.php?controller=pjAdminBuses&action=pjActionGetLocations&route_id=" + route_id + qs).done(function (data) {
					$('#bs_bus_locations').html(data);
					$('.bs-loader').css('display', 'none');
					if ($('.pj-timepicker').length) {
			        	$( ".pj-timepicker" ).each(function( index ) {
			        		var $this = $(this);
			        		$this.clockpicker({
			                	twelvehour: myLabel.showperiod,
			                	autoclose: true,
			                	afterDone: function() {
			            			
			                    }
			                });
			    		});
			        };
				});
			}
		}).on("click", ".pj-button-add-date", function (e) {
			var clone_text = $('#bs_date_clone').html();
			$('#bs_date_container').append(clone_text);
			if($('#bs_date_container').find('.form-group').length > 0)
			{
				$('.pjBrsNoDates').hide();
			}else{
				$('.pjBrsNoDates').show();
			}
			if ($('.datepick').length > 0) {
            	$('.datepick').datepicker({autoclose: true});
			}
		}).on("click", ".pj-button-remove-date", function (e) {
			$(this).closest('.pj-date-item').remove();
			if($('#bs_date_container').find('.form-group').length > 0)
			{
				$('.pjBrsNoDates').hide();
			}else{
				$('.pjBrsNoDates').show();
			}
		}).on("click", '.pj-add-ticket', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var clone_text = $('#bs_ticket_clone').html(),
				index = Math.ceil(Math.random() * 999999),
				number_of_tickets = $('#bs_ticket_list').find(".bs-ticket-row").length,
				order = parseInt(number_of_tickets, 10) + 1;
			clone_text = clone_text.replace(/\{INDEX\}/g, 'bs_' + index);
			clone_text = clone_text.replace(/\{ORDER\}/g, order);
			$('#bs_ticket_list').append(clone_text);
			if($(".touchspin3-bs_" + index).length > 0)
			{
				$(".touchspin3-bs_" + index).TouchSpin({
					min: 0,
					max: 4294967295,
					step: 1,
					verticalbuttons: true,
		            buttondown_class: 'btn btn-white',
		            buttonup_class: 'btn btn-white'
		        }).on('touchspin.on.startspin', function () {
		        	
		        });
			}
		}).on("click", '.pj-remove-ticket', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $ticket = $(this).closest('.bs-ticket-row'),
				id = $ticket.attr('data-index');
			if(id.indexOf("bs") == -1)
			{
				remove_arr.push(id);
			}
			$('#remove_arr').val(remove_arr.join("|"));
			$ticket.remove();
			
			$('#bs_ticket_list').find(".bs-ticket-row").each(function (order, row) {
				var index = $(row).attr('data-index'),
					title = myLabel.ticket + " " + (order + 1) + ":";
				$('.bs-title-' + index).html(title);
			});
		}).on("change", "#ticket_id", function (e) {
			var ticket_id = $(this).val(),
			    qs = '&bus_id=' + $('#id').val();
			$('.bs-loader').css('display', 'block');
			$.get("index.php?controller=pjAdminBuses&action=pjActionGetPriceGrid&ticket_id=" + ticket_id + qs).done(function (data) {
				$('#bs_price_grid').html(data);
				$('.bs-loader').css('display', 'none');
				if ($('.tblTicketPricesGrid').length > 0) {
					$('.tblTicketPricesGrid').tableHeadFixer({"left" : 1});
				}
			});
		}).on("focusin", ".pj-grid-field", function(e){
			$(this).select();
		}).on("change", ".onoffswitch-seats-count .onoffswitch-checkbox", function (e) {
			if ($(this).prop('checked')) {
				$('.pj-ticket-count').removeClass('pj-hide-count');
            }else {
            	$('.pj-ticket-count').addClass('pj-hide-count');
            }
		}).on("click", ".pj-copy-ticket", function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if (myLabel.buses > 0) {
				$('#modalCopyPrice').modal('show');
			} else {
				swal({
	    			title: '',
					text: myLabel.alert_no_copy_price_text,
					type: "warning",
					confirmButtonColor: "#DD6B55",
					confirmButtonText: myLabel.btn_close,
					closeOnConfirm: false,
					showLoaderOnConfirm: false
				}, function () {
					swal.close();
				});
			}
		}).on("change", "#source_bus_id", function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$.get("index.php?controller=pjAdminBuses&action=pjActionGetTickets&bus_id=" + $(this).val()).done(function (data) {
				$('#ticketTypeBox').html(data);
			});
		}).on("click", ".btnCopyPrice", function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if($('#source_bus_id').val() != '' && $('#source_ticket_id').val() != '')
			{
				$.post("index.php?controller=pjAdminBuses&action=pjActionCopyPrices&bus_id="+$('#id').val()+"&ticket_id=" + $('#ticket_id').val(), $frmCopyPrice.serialize()).done(function (data) {
					if(data.code == '200')
					{
						$('#modalCopyPrice').modal('hide');
						window.location.href = "index.php?controller=pjAdminBuses&action=pjActionPrice&id="+$('#id').val()+"&ticket_id=" + $('#ticket_id').val() + "&err=APC01";
					}
				});
			}
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
		});
	});
})(jQuery);