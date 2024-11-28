var jQuery = jQuery || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateRoute = $("#frmCreateRoute"),
			$frmUpdateRoute = $("#frmUpdateRoute"),
			$pjFdFormWrapper = $("#pjFdFormWrapper"),
			multilang = ($.fn.multilang !== undefined),
			dialog = ($.fn.dialog !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			locale_id = myLabel.localeId,
			$tr = null;
		
		function setLocations()
		{
			var index_arr = new Array();
			
			$('#bs_location_list').find(".bs-location-row").each(function (index, row) {
				index_arr.push($(row).attr('data-index'));
			});
			$('#index_arr').val(index_arr.join("|"));
		}
				
		if (multilang && 'pjCmsLocale' in window) {
			$(".multilang").multilang({
				langs: pjCmsLocale.langs,
				flagPath: pjCmsLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					locale_id = ui.index;
				}
			});
		}
		function getCreateForm()
		{
			$.get("index.php?controller=pjAdminRoutes&action=pjActionCreateForm").done(function (data) {
				$pjFdFormWrapper.html(data);
				bindCreateForm();
			});
		}
		if ($pjFdFormWrapper.length > 0 && myLabel.has_create) 
		{
			getCreateForm();
		}
		function highlightLanguage()
		{
			$(".pj-form-langbar-item").removeClass('btn-primary').removeClass('btn-white');
			$(".pj-form-langbar-item").each(function( index ) {
				if($(this).attr('data-index') == myLabel.localeId)
				{
					$(this).addClass('btn-primary');
				}
			});
			$(".pj-multilang-wrap").each(function( index ) {
				if($(this).attr('data-index') == myLabel.localeId)
				{
					$(this).css('display','block');
				}else{
					$(this).css('display','none');
				}
			});
		}
		function bindCreateForm()
		{
			$frmCreateRoute = $("#frmCreateRoute");
			if ($frmCreateRoute.length > 0 && validate) {
				$frmCreateRoute.validate({
					invalidHandler: function (event, validator) {
					    $(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).addClass('btn-primary');
							}else{
								$(this).removeClass('btn-primary');
							}
						});
					},
					ignore: "",
					submitHandler: function(form){
						var ladda_buttons = $(form).find('.ladda-button');
					    if(ladda_buttons.length > 0)
	                    {
	                        var l = ladda_buttons.ladda();
	                        l.ladda('start');
	                    }
					    setLocations();
					    $.post("index.php?controller=pjAdminRoutes&action=pjActionCreate", $(form).serialize()).done(function (data) {
					    	l.ladda('stop');
					    	if(data.status == 'OK')
					    	{
					    		getCreateForm();
					    		var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$.extend(cache, {
									status: "",
									q: ""
								});
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjAdminRoutes&action=pjActionGetRoute", "title", "ASC", content.page, content.rowCount);
					    	}else if(data.code == '104'){
					    		swal({
					    			title: "",
									text: data.text,
									type: "warning",
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "OK",
									closeOnConfirm: false,
									showLoaderOnConfirm: false
								}, function () {
									swal.close();
								});
					    	}
						});
						return false;
					}
				});
				highlightLanguage();
				
				$("#bs_location_list").sortable({
					handle : '.location-move-icon',
					stop: function(e){
						$('#bs_location_list').find(".bs-location-row").each(function (order, row) {
							var index = $(row).attr('data-index'),
								title = myLabel.location + " " + (order + 1) + ":";
							$('.bs-title-' + index).html(title);
						});
					}
			    });
			}
		}
		function bindUpdateForm()
		{
			$frmUpdateRoute = $("#frmUpdateRoute");
			if ($frmUpdateRoute.length > 0 && validate) {
				$frmUpdateRoute.validate({
					invalidHandler: function (event, validator) {
					    $(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).addClass('btn-primary');
							}else{
								$(this).removeClass('btn-primary');
							}
						});
					},
					ignore: "",
					submitHandler: function(form){
						var ladda_buttons = $(form).find('.ladda-button');
					    if(ladda_buttons.length > 0)
	                    {
	                        var l = ladda_buttons.ladda();
	                        l.ladda('start');
	                    }
					    setLocations();
					    $.post("index.php?controller=pjAdminRoutes&action=pjActionUpdate", $(form).serialize()).done(function (data) {
					    	l.ladda('stop');
					    	if(data.status == 'OK')
					    	{
					    		getCreateForm();
					    		var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$.extend(cache, {
									status: "",
									q: ""
								});
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjAdminRoutes&action=pjActionGetRoute", "title", "ASC", content.page, content.rowCount);
					    	}else if(data.code == '105'){
					    		swal({
					    			title: "",
									text: data.text,
									type: "warning",
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "OK",
									closeOnConfirm: false,
									showLoaderOnConfirm: false
								}, function () {
									swal.close();
								});
					    	}
						});
						return false;
					}
				});
				highlightLanguage();
				
				$("#bs_location_list").sortable({
					handle : '.location-move-icon',
					stop: function(e){
						$('#bs_location_list').find(".bs-location-row").each(function (order, row) {
							var index = $(row).attr('data-index'),
								title = myLabel.location + " " + (order + 1) + ":";
							$('.bs-title-' + index).html(title);
						});
					}
			    });
			}
		}

		if ($("#grid").length > 0 && datagrid) {
			var $buttons = [];
			var $actions = [];
			var $editable = false;
			var $select = false;
			if (myLabel.has_update) {
				$editable = true;
				$buttons.push({type: "edit", url: "index.php?controller=pjAdminRoutes&action=pjActionUpdateForm&id={:id}"});
			}
			if (myLabel.has_delete) {
				$buttons.push({type: "delete", url: "index.php?controller=pjAdminRoutes&action=pjActionDeleteRoute&id={:id}"});
			}
			if (myLabel.has_create) {
				$buttons.push({type: "menu", url: "#", text: myLabel.menu, items: [
	                              {text: myLabel.copy_route, url: "index.php?controller=pjAdminRoutes&action=pjActionCreateForm&type=copy&from_id={:id}"}, 
	                              {text: myLabel.reverse_route, url: "index.php?controller=pjAdminRoutes&action=pjActionCreateForm&type=reverse&from_id={:id}"}
	                          ]});
			}
			if (myLabel.has_delete_bulk) {
				$actions.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminRoutes&action=pjActionDeleteRouteBulk", render: true, confirmation: myLabel.delete_confirmation});
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
				columns: [{text: myLabel.title, type: "text", sortable: true, editable: $editable},
				          {text: myLabel.from, type: "text", sortable: true, editable: false},
				          {text: myLabel.to, type: "text", sortable: true, editable: false},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: $editable, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"}
				          ],
				dataUrl: "index.php?controller=pjAdminRoutes&action=pjActionGetRoute",
				dataType: "json",
				fields: ['title', 'from', 'to', 'status'],
				paginator: {
					actions: $actions,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminRoutes&action=pjActionSaveRoute&id={:id}",
				select: $select
			});
		}
		
		if(myLabel.trigger_create == 1 && myLabel.has_create)
		{
			getCreateForm();
		}
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminRoutes&action=pjActionGetRoute", "title", "ASC", content.page, content.rowCount);
			
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminRoutes&action=pjActionGetRoute", "title", "ASC", content.page, content.rowCount);
			
		}).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminRoutes&action=pjActionGetRoute", "title", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pjFdAddRoute", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCreateForm();
			if($tr != null)
			{
				$tr.find('.pj-table-icon-edit').show();
				$tr.find('.pj-table-icon-delete').show();
				$tr = null;
			}
		}).on("click", ".pjFdBtnCancel", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if (myLabel.has_create) {
				getCreateForm();
			} else {
				$pjFdFormWrapper.html('');
			}			
			if($tr != null)
			{
				$tr.find('.pj-table-icon-edit').show();
				$tr.find('.pj-table-icon-delete').show();
				$tr = null;
			}
		}).on("click", ".pj-table-icon-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var href = $this.attr('href');
			$.get(href).done(function (data) {
				$pjFdFormWrapper.html(data);
				bindUpdateForm();
				if($tr != null)
				{
					$tr.find('.pj-table-icon-edit').show();
					$tr.find('.pj-table-icon-delete').show();
					$tr = null;
				}
				$tr = $this.closest("tr");
				$tr.find('.pj-table-icon-edit').hide();
				$tr.find('.pj-table-icon-delete').hide();
			});
		}).on("click", '.pj-add-location', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var clone_text = $('#bs_location_clone').html(),
				index = Math.ceil(Math.random() * 999999),
				number_of_locations = $('#bs_location_list').find(".bs-location-row").length,
				order = parseInt(number_of_locations, 10) + 1;
			if(number_of_locations < myLabel.number_of_cities)
			{
				clone_text = clone_text.replace(/\{INDEX\}/g, 'bs_' + index);
				clone_text = clone_text.replace(/\{ORDER\}/g, order);
				$('#bs_location_list').append(clone_text);
			}
		}).on("click", '.location-delete-icon', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $location = $(this).closest('.bs-location-row');
			$location.remove();
			
			$('#bs_location_list').find(".bs-location-row").each(function (order, row) {
				var index = $(row).attr('data-index'),
					title = myLabel.location + " " + (order + 1) + ":";
				$('.bs-title-' + index).html(title);
			});
		}).on("focusin", '.pj-grid-field', function(e){
			$(this).select();
		}).on("change", '.bs-city', function(e){
			var $this = $(this);
			$('#bs_location_list').find(".bs-city").each(function (order, ele) {
				if($(ele).attr('name') != $this.attr('name') && $this.val() == $(ele).val())
				{
					swal({
		    			title: myLabel.same_location_title,
						text: myLabel.same_location_text,
						type: "warning",
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "OK",
						closeOnConfirm: false,
						showLoaderOnConfirm: false
					}, function () {
						swal.close();
					});
					$this.val('');
				}
			});
		}).on("click", "#grid tbody tr td ul.dropdown-menu li a", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var href = $this.attr('href');
			$.get(href).done(function (data) {
				$pjFdFormWrapper.html(data);
				bindCreateForm();
			});
		});
	});
})(jQuery);