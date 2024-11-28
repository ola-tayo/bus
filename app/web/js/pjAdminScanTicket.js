var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		
		function docReady(fn) {
	        // see if DOM is already available
	        if (document.readyState === "complete"
	            || document.readyState === "interactive") {
	            // call on next available tick
	            setTimeout(fn, 1);
	        } else {
	            document.addEventListener("DOMContentLoaded", fn);
	        }
	    }
		
		docReady(function () {
			docReady(function () {
		        var lastResult;
		        var cameraStarted = 0;
		        
		        function stopScanning() {
		        	if (cameraStarted == 1) {
			        	html5QrCode.stop().then((ignore) => {
							cameraStarted = 0;
						    // QR Code scanning is stopped.
						}).catch((err) => {
						    // Stop failed, handle it.
						});
		        	}
		        }
		        
				const html5QrCode = new Html5Qrcode(
						"qr-reader", { formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ] });
				const qrCodeSuccessCallback = (decodedText, decodedResult) => {
				    /* handle success */
					if (decodedText !== lastResult) {
		        		lastResult = decodedText;
			        	$.post(["index.php?controller=pjAdminScanTicket&action=pjActionCheckTicket"].join(""), {code: decodedText}).done(function (data) {
		                	if (data.status == 'OK') {
		                		$('#qr-reader-results').removeClass("alert alert-danger").addClass("alert alert-success");
		                		$('#qr-reader-results').html(data.text);
		                	} else {
		                		$('#qr-reader-results').removeClass("alert alert-success").addClass("alert alert-danger");
		                		$('#qr-reader-results').html(data.text);
		                	}
		                	$('#qr-reader-results').show();
							$('.bsBtnScanAnImageFile').show();
							$('.bsBtnStopScanning').hide();
		                	stopScanning();
						});
		        	}
				};
				const config = { fps: 10, qrbox: { width: 250, height: 250 } };
				$('.pjBsDriverWrap').on("click.bs", ".bsBtnStartScanning", function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback).catch((err) => {
						// Start failed, handle it.
						$('.bsBtnStopScanning').hide();
						$('#qr-reader-results').removeClass("alert alert-success").addClass("alert alert-danger");
                		$('#qr-reader-results').html(err);
                		$('#qr-reader-results').show();
					});
					cameraStarted = 1;
					$('#qr-input-file-reader').hide();
					$('.bsBtnStopScanning').show();
					$('.bsBtnScanAnImageFile').hide();
					$('#qr-reader-results').hide();
				}).on("click.bs", ".bsBtnStopScanning", function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					stopScanning();
					$('.bsBtnScanAnImageFile').show();
					$('.bsBtnStopScanning').hide();
				}).on("click.bs", ".bsBtnScanAnImageFile", function (e) {
					if (e && e.preventDefault) {
						e.preventDefault();
					}
					$('#qr-input-file-reader').show();
					$('.bsBtnStopScanning').hide();
					$('#qr-reader-results').hide();
					stopScanning();
				});
				
				// File based scanning
				const fileinput = document.getElementById('qr-input-file');
				fileinput.addEventListener('change', e => {
				    if (e.target.files.length == 0) {
				        // No file selected, ignore 
				    	console.log('No file selected, ignore');
				        return;
				    }

				    const imageFile = e.target.files[0];
				    // Scan QR Code
				    html5QrCode.scanFile(imageFile, true)
				        .then(decodedText => {
				            // success, use decodedText
				        	if (decodedText !== lastResult) {
				        		lastResult = decodedText;
					        	$.post(["index.php?controller=pjAdminScanTicket&action=pjActionCheckTicket"].join(""), {code: decodedText}).done(function (data) {
				                	if (data.status == 'OK') {
				                		$('#qr-reader-results').removeClass("alert alert-danger").addClass("alert alert-success");
				                		$('#qr-reader-results').html(data.text);
				                	} else {
				                		$('#qr-reader-results').removeClass("alert alert-success").addClass("alert alert-danger");
				                		$('#qr-reader-results').html(data.text);
				                	}
				                	$('#qr-reader-results').show();
									$('.bsBtnScanAnImageFile').show();
									$('.bsBtnStopScanning').hide()
				                	stopScanning();
								});
				        	}
				        })
				        .catch(err => {
				            // failure, handle it.
				            console.log('Error scanning file. Reason: ${err}');
				        });
				});
		    });
	    });
	});
})(jQuery_1_8_2);