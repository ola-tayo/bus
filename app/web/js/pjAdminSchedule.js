var jQuery = jQuery || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var 
			$frmSchedule = $('#frmSchedule'),
			datagrid = ($.fn.datagrid !== undefined),
			datepicker = ($.fn.datepicker !== undefined);
		
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
	        	if($('#boxSchedule').length > 0)
				{
	        		$(this).closest('form').trigger('submit');
				}
				if($('#boxTimetable').length > 0)
				{
					getTimetable('date');
				}
			});
        };
        
        if ($("#grid").length > 0 && datagrid) {
        	function formatButtonAddBooking(str, obj) {
        		return '<a class="btn btn-primary btn-outline" href="index.php?controller=pjAdminBookings&action=pjActionCreate&bus_id='+obj.id+'&date='+obj.date+'&pickup_id='+obj.pickup_id+'&return_id='+obj.return_id+'"><i class="fa fa-plus"></id> '+myLabel.add_booking+'</a>';
        	}
			var $actions = [],
				$buttons = [],
				$columns = [{text: myLabel.bus, type: "text", sortable: true, editable: false},
					          {text: myLabel.departure, type: "text", sortable: true, editable: false},
					          {text: myLabel.arrival, type: "text", sortable: true, editable: false},
					          {text: myLabel.tickets, type: "text", sortable: true, editable: false},
					          {text: myLabel.total_tickets, type: "text", sortable: true, editable: false}
					       ],
				$fields = ['route', 'departure_time', 'arrival_time', 'tickets', 'total_tickets'];

			if (pjGrid.hasAccessCreateBooking) 
			{
				$columns.push({text: '', type: "text", sortable: false, editable: false, align: 'right', renderer: formatButtonAddBooking});
				$fields.push('id')
			}
			
			var $items = [];
			if (pjGrid.hasAccessScheduleBooking) {
				$items.push({text: myLabel.passengers_list, url: "index.php?controller=pjAdminSchedule&action=pjActionBookings&bus_id={:id}&date={:date}"});
			}
			if (pjGrid.hasAccessScheduleSeats) {
				$items.push({text: myLabel.seats_list, url: "index.php?controller=pjAdminSchedule&action=pjActionSeats&bus_id={:id}&date={:date}"});
			}
			if (pjGrid.hasAccessScheduleBooking) {
				$items.push({text: myLabel.print_passengers_list, url: "index.php?controller=pjAdminSchedule&action=pjActionPrintBookings&bus_id={:id}&date={:iso_date}"});
			}
			if (pjGrid.hasAccessScheduleSeats) {
				$items.push({text: myLabel.print_seats_list, url: "index.php?controller=pjAdminSchedule&action=pjActionPrintSeats&bus_id={:id}&date={:iso_date}"});
			}
			if (pjGrid.hasAccessBookings) {
				$items.push({text: myLabel.view_trip_booking, url: "index.php?controller=pjAdminBookings&action=pjActionIndex&bus_id={:id}"});
			}
			if (pjGrid.hasAccessUpdateBus) {
				$items.push({text: myLabel.edit_bus, url: "index.php?controller=pjAdminBuses&action=pjActionTime&id={:id}"});
			}
			if (pjGrid.hasAccessNotOperatingBus) {
				$items.push({text: myLabel.cancel_bus, url: "index.php?controller=pjAdminBuses&action=pjActionNotOperating&id={:id}&date={:date}"});
			}
			if ($items.length > 0) {
				$buttons.push({type: "menu", url: "#", text: myLabel.menu, items: $items});
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
				columns: $columns,
				dataUrl: "index.php?controller=pjAdminSchedule&action=pjActionGetSchedule",
				dataType: "json",
				fields: $fields,
				paginator: {
					actions: $actions,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: null,
				select: false,
				onRender: function(){
					var $content = $grid.datagrid("option", "content"),
						$frm = $('.frm-filter'),
						$date = $frm.find("input[name='schedule_date']").val(),
						$route_id = $frm.find("select[name='route_id']").val();
					$('#bs_print_schedule').attr('href', "index.php?controller=pjAdminSchedule&action=pjActionPrintSchedule&date=" + $date + "&route_id=" + $route_id + "&column=" + $content.column + "&direction=" + $content.direction)
				}
			});
		}
		
		function getTimetable(mode) 
		{
			var selected_date = $('#selected_date').val(),
				route_id = $('#route_id').val(),
				opts = {};
			if(mode == 'route' || mode == 'date')
			{
				opts = {
					route_id: route_id,
					selected_date: selected_date
				}
			}else if(mode == 'next'){
				var $next_link = $('#bs_next_week');
				opts = {
					route_id: route_id,
					week_start_date: $next_link.attr('data-week_start'),
					week_end_date: $next_link.attr('data-week_end'),
					selected_date: selected_date
				}
			}else if(mode == 'prev'){
				var $prev_link = $('#bs_prev_week');
				opts = {
					route_id: route_id,
					week_start_date: $prev_link.attr('data-week_start'),
					week_end_date: $prev_link.attr('data-week_end'),
					selected_date: selected_date
				}
			}
			
			$('.bs-loader').css('display', 'block');
			$.get("index.php?controller=pjAdminSchedule&action=pjActionGetTimetable", opts).done(function (data) {
				$("#boxTimetable").html(data);
				if ($('.tblTimetableGrid').length > 0) {
					$('.tblTimetableGrid').tableHeadFixer({"left" : 1});
				}
				$('[data-toggle="tooltip"]').tooltip();
				$('.bs-loader').css('display', 'none');
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
				date: $this.find("input[name='schedule_date']").val(),
				route_id: $this.find("select[name='route_id']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminSchedule&action=pjActionGetSchedule", content.column, content.direction, content.page, content.rowCount);
			return false;
		}).on("click", ".btnFilter", function (e) {
			var dateText = $(this).attr('rev');
			$('#schedule_date').val(dateText);
			$(this).closest('form').trigger('submit');
		}).on("change", "#filter_route_id", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).closest('form').trigger('submit');
			return false;
		}).on("change", "#location_id", function (e) {
			var href = $(this).attr('data-href') + '&location_id=' + $(this).val(),
				print_href = href.replace("pjActionGetBookings", "pjActionPrintBookings");
			$('.bs-loader').css('display', 'block');
			$.get(href, {
				
			}).done(function (data) {
				$("#boxBookings").html(data);
				$("#bs_print_booking").attr('href', print_href);
				$('.bs-loader').css('display', 'none');
			});
		}).on("change", "#route_id", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getTimetable('route');
		}).on("click", "#bs_next_week", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getTimetable('next');
		}).on("click", "#bs_prev_week", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getTimetable('prev');
		});
		
		if ($('.tblTimetableGrid').length > 0) {
			$('.tblTimetableGrid').tableHeadFixer({"left" : 1});
			$('[data-toggle="tooltip"]').tooltip();
		}
		if ($('.tblSeatsGrid').length > 0) {
			$('.tblSeatsGrid').tableHeadFixer({"left" : 1});
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
})(jQuery);