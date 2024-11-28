var jQuery = jQuery || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateBusType = $("#frmCreateBusType"),
			$frmUpdateBusType = $("#frmUpdateBusType"),
			$boxMap = $("#boxMap"),
			datagrid = ($.fn.datagrid !== undefined),
			validate = ($.fn.validate !== undefined),
			multilang = ($.fn.multilang !== undefined),
			hotspot_width = 25,
			hotspot_height = 25,
			vOpts = {
				rules: {
					seats_count: {
						required: function(){
							if($('#use_map').is(":checked"))
							{
								return false;
							}else{
								return true;
							}
						}
					}
				},
				messages: {
					number_of_seats:{
						required: myLabel.seats_required
					}
				},
				errorPlacement: function (error, element) {
					error.insertAfter(element.parent());
				},
				onkeyup: false,
				ignore: '',
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
			};
		
		if (multilang && 'pjCmsLocale' in window) {
			$(".multilang").multilang({
				langs: pjCmsLocale.langs,
				flagPath: pjCmsLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					
				}
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
		
		function collisionDetect(o) {
			var i, pos, horizontalMatch, verticalMatch, collision = false;
			$("#mapHolder").children("span").each(function (i) {
				pos = getPositions(this);
				horizontalMatch = comparePositions([o.left, o.left + o.width], pos[0]);
				verticalMatch = comparePositions([o.top, o.top + o.height], pos[1]);			
				if (horizontalMatch && verticalMatch) {
					collision = true;
					return false;
				}
			});
			if (collision) {
				return true;
			}
			return false;
		}
		function getPositions(box) {
			var $box = $(box);
			var pos = $box.position();
			var width = $box.width();
			var height = $box.height();
			return [[pos.left, pos.left + width], [pos.top, pos.top + height]];
		}
		
		function comparePositions(p1, p2) {
			var x1 = p1[0] < p2[0] ? p1 : p2;
			var x2 = p1[0] < p2[0] ? p2 : p1;
			return x1[1] > x2[0] || x1[0] === x2[0] ? true : false;
		}
		
		function updateElem(event, ui) {
			var $this = $(this),
				rel = $this.attr("rel"),
				$hidden = $("#" + rel),
				val = $hidden.val().split("|");				
			$hidden.val([val[0], parseInt($this.width(), 10), parseInt($this.height(), 10), ui.position.left, ui.position.top, $this.text(), val[6], val[7]].join("|"));
		}
		function getMax() {
			var tmp, index = 0;
			$("span.empty").each(function (i) {
				if (!isNaN(Number(this.title))) {
					tmp = Number(this.title);
				} else {
					tmp = parseInt($(this).attr("rel").split("_")[1], 10);
				}
				if (tmp > index) {
					index = tmp;
				}
			});
			return index;
		}
		
		function GetZoomFactor () {
            var factor = 1;
            if (document.body.getBoundingClientRect) {
                    // rect is only in physical pixel size in IE before version 8 
                var rect = document.body.getBoundingClientRect ();
                var physicalW = rect.right - rect.left;
                var logicalW = document.body.offsetWidth;

                    // the zoom level is always an integer percent value
                factor = Math.round ((physicalW / logicalW) * 100) / 100;
            }
            return factor;
        }
		
		if ($frmCreateBusType.length > 0 && validate) {
			var validator = $frmCreateBusType.submit(function() {

			}).validate(vOpts);
		}
		if ($frmUpdateBusType.length > 0) {
			var validator = $frmUpdateBusType.submit(function() {
				if($('#hiddenHolder').length > 0)
				{
					if($("#hiddenHolder :input").length > 0)
					{
						$('#number_of_seats').val('1');
					}else{
						$('#number_of_seats').val('');
					}
				}
			}).validate(vOpts);
			
			var offset = $("#map").offset(),
				dragOpts = {
					containment: "parent",
					stop: function (event, ui) {
						updateElem.apply(this, [event, ui]);
					}
				};
			$("span.empty").draggable(dragOpts).resizable({
				resize: function(e, ui) {
					var height = $(this).height();
					$(this).css("line-height", height + "px"); 
		        },
				stop: function(e, ui) {
					var height = $(this).height();
					$(this).css("line-height", height + "px");
					updateElem.apply(this, [e, ui]);
		        }
			}).bind("click", function (e) {
				$('#pj_delete_seat').attr('data-rel', $(this).attr("rel"));
				$('#pj_delete_seat').html(myLabel.btn_delete + " " + $(this).attr("title"))
				$('#pj_delete_seat').show();
				$(this).siblings(".rect").removeClass("rect-selected").end().addClass("rect-selected");
			});
			
			$("#mapHolder").click(function (e) {
				var px = $('.bsMapHolder').scrollLeft();
				var $this = $(this),
				index = getMax(),
				w = hotspot_width,
				h = hotspot_height;
				
				var t = Math.ceil(e.pageY - offset.top - (parseInt(hotspot_height / 2, 10)));
				var l = Math.ceil(e.pageX - offset.left - (parseInt(hotspot_width / 2, 10)) + px);
				var o = {top: t, left: l, width: w, height: h};
				
				if (!collisionDetect(o)) {
					index++;
					$("<span>", {
						css: {
							"top": t + "px",
							"left": l + "px",
							"width": w + "px",
							"height": h + "px",
							"line-height": h + "px",
							"position": "absolute"
						},
						html: '<span class="bsInnerRect" data-name="hidden_'+index+'">'+index+'</span>',
						rel: "hidden_" + index,
						title: index
					}).addClass("rect empty new").draggable(dragOpts).resizable({
						resize: function(e, ui) {
							var height = $(this).height();
							$(this).css("line-height", height + "px"); 
				        },
						stop: function(e, ui) {
							var height = $(this).height();
							$(this).css("line-height", height + "px"); 
							updateElem.apply(this, [e, ui]);
				        }
					}).bind("click", function (e) {
						$('#pj_delete_seat').attr('data-rel', $(this).attr("rel"));
						$('#pj_delete_seat').html(myLabel.btn_delete + " " + $(this).attr("title"))
						$('#pj_delete_seat').show();
						$(this).siblings(".rect").removeClass("rect-selected").end().addClass("rect-selected");
					}).appendTo($this);
					
					$("<input>", {
						type: "hidden",
						name: "seats_new[]",
						id: "hidden_" + index
					}).val(['x', w, h, l, t, index, '1', '1'].join("|")).appendTo($("#hiddenHolder"));
					
				} else {
					if (window.console && window.console.log) {
					}
				}
			});
		}
		
		function formatMap(val, obj) {
			return val != null ? myLabel.yes : myLabel.no ;
		}
		
		if ($("#grid").length > 0 && datagrid) {			
			var $buttons = [];
			var $actions = [];
			var $editable = false;
			var $select = false;

			if (pjGrid.hasAccessUpdate)
			{
				$editable = true;
				$buttons.push({type: "edit", url: "index.php?controller=pjAdminBusTypes&action=pjActionUpdate&id={:id}"});
			}			
			if (pjGrid.hasAccessDeleteSingle)
			{
				$buttons.push({type: "delete", url: "index.php?controller=pjAdminBusTypes&action=pjActionDeleteBusType&id={:id}"});
			}
			if (pjGrid.hasAccessDeleteMulti) 
			{
				$actions.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminBusTypes&action=pjActionDeleteBusTypeBulk", render: true, confirmation: myLabel.delete_confirmation});
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
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: $editable},
				          {text: myLabel.map, type: "text", sortable: false, editable: false, renderer: formatMap},
				          {text: myLabel.seats, type: "text", sortable: true, editable: false, align: "center"},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: $editable, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"}],
				dataUrl: "index.php?controller=pjAdminBusTypes&action=pjActionGetBusType",
				dataType: "json",
				fields: ['name', 'seats_map', 'seats_count', 'status'],
				paginator: {
					actions: $actions,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBusTypes&action=pjActionSaveBusType&id={:id}",
				select: $select
			});
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
			$grid.datagrid("load", "index.php?controller=pjAdminBusTypes&action=pjActionGetBusType", "name", "ASC", content.page, content.rowCount);
			return false;
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
			$grid.datagrid("load", "index.php?controller=pjAdminBusTypes&action=pjActionGetBusType", "name", "ASC", content.page, content.rowCount);
			return false;
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
			$grid.datagrid("load", "index.php?controller=pjAdminBusTypes&action=pjActionGetBusType", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("change", "#seats_map", function (e) {
			if($(this).val() != '')
			{
				$('#seats_count').val('');
			}
			return false;
		}).on("click", ".pj-delete-map", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $id = $(this).attr('data-id');
			swal({
				title: myLabel.alert_del_map_title,
				text: myLabel.alert_del_map_text,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: myLabel.btn_delete,
				cancelButtonText: myLabel.btn_cancel,
				closeOnConfirm: false,
				showLoaderOnConfirm: true
			}, function () {
				$.post("index.php?controller=pjAdminBusTypes&action=pjActionDeleteMap", {id: $id}).done(function (data) {
					swal.close();
					if(data != '100')
					{
						$boxMap.html('');
						$('.pjUploadMapWrap').html(data);
					}
				});
			});
		}).on("click", "#pj_delete_seat", function (e) {
			var rel = $(this).attr('data-rel');
			$("#" + rel, $("#hiddenHolder")).remove();				
			$(".rect-selected[rel='"+ rel +"']", $("#mapHolder")).remove();
			$(this).css('display', 'none');
		}).on("click", "input:checkbox[name=use_map]", function (e) {
			var $has_map = parseInt($(this).attr('data-has_map'), 10);
			if(this.checked)
			{
				$('.bsUseMapNo').css('display', 'none');
				$('#seats_count').removeClass('required');
				$('#boxMap').show();
				if ($has_map == 1) {
					$('.pjDeleteMapWrap').show();
					$('.bsUseMapYes').css('display', 'none');
					$('#seats_map').removeClass('required');
				} else {
					$('.pjDeleteMapWrap').hide();
					$('.bsUseMapYes').css('display', 'block');
					$('#seats_map').addClass('required');
				}
			}else{
				$('.pjDeleteMapWrap').hide();
				$('.bsUseMapYes').css('display', 'none');
				$('.bsUseMapNo').css('display', 'block');
				$('#seats_count').addClass('required');
				$('#seats_map').removeClass('required');
				$('#boxMap').hide();
			}
		});
	});
})(jQuery);