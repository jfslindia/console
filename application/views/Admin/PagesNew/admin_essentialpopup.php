
<div class="inner-wrapper">
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Essential Popup</h2>

			<div class="right-wrapper text-right">
				<ol class="breadcrumbs">
					<li>
						<a href="<?php echo base_url(); ?>console/home">
							<i class="fas fa-home"></i>
						</a>
					</li>
					<li><span>Fabhome</span></li>
					<li><span> Orders</span></li>
					<?php if (ADMIN_PREVILIGE == 'root') { ?>
						<li><span><a href="<?php echo site_url('Console_Controller/admin_fab_home'); ?>">Back To Rate Page</a></span>
						</li>
					<?php } ?>
				</ol>

				<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
			</div>
		</header>



		<section class="card" >
			<div class="card-body">
				<div class="media-gallery">
					<div class="inner-menu-content">

					</div>
					<div class="row mg-files" data-sort-destination data-sort-id="media-gallery">

						<div class="isotope-item document col-sm-6 col-md-4 col-lg-3" >
							<div class="thumbnail">
								<div class="thumb-preview">
									<div id="" class="uk-card uk-card-default">
										<div id="new_offer_img_block" class="uk-card-media-top uk-text-center">
											<div class="js-upload" uk-form-custom>
												<input type="file" multiple>
												<button uk-icon="icon: plus-circle; ratio: 3.5"
														class="uk-button uk-button-default uk-padding" type="button"
														tabindex="-1"></button>

											</div>
											<div class="hide">Maximum height: 626px<br>Maximum width: 1280px</div>
										</div>
										<div class="uk-card-body">
											<span class="uk-card-title new_editable uk-input" id="new_title"
												contenteditable="true" value="">New Essentialpopup</span>
										</div>
										<div class="uk-card-body">
											<span class="uk-card-title new_editable uk-input" id="new_site_url"
												contenteditable="true" value="">Site URL</span>
										</div>
										<div class="uk-card-body" class="default_class">
											<span><input type="checkbox" class ="uk-checkbox" id="default">   Set as default</span>
										</div>
										<div class="uk-card-body">
											<select class="uk-select" id="state">
												<option value="" class="uk-h4">Select a state and cities</option>
												<?php for($i=0;$i<sizeof($states);$i++){?>
													<option value="<?php echo $states[$i]['statecode'];?>" class="uk-h4"><?php echo $states[$i]['statename'];?></option>
												<?php } ?>
												<option value="all" class="uk-h4">ALL</option>
											</select>
										</div>
										<div class="uk-card-body">
											<div class="uk-form-controls">
												<?php for($i=0;$i<=sizeof($states);$i++){?>
													<div id="<?php echo $i;?>" class="uk-h4">
														<?php for($j=0;$j<sizeof($cities[$i]);$j++){?>
															<input type="checkbox" class="uk-checkbox" name="cities" id="cities" value="<?php echo $cities[$i][$j]['cityname']; ?>"> <?php echo $cities[$i][$j]['cityname']; ?>
														<?php }?>
													</div>
												<?php }?>
											</div>
										</div>
										<div class="uk-card-body">
											<select id="new_brand" class="new_editable_brand uk-select">
												<option value="PCT0000001" selected>Fabricspa</option>
												<option value="PCT0000014">Click2Wash</option>

											</select>
										</div>
										<div class="uk-card-footer">
											<button onclick="add_essentialpopups()"
													id="new_save"
													class="uk-button uk-button-default">Add
											</button>

										</div>
									</div>
								</div>
							</div>
						</div>


						<?php for ($i = 0; $i < sizeof($popups); $i++) { ?>
						<div class="isotope-item document col-sm-6 col-md-4 col-lg-3">
							<div class="thumbnail  thumbnail-selected">
								<div class="thumb-preview">
									<a class="thumb-image" href="<?php echo $popups[$i]['url']; ?>">
										<img src="<?php echo $popups[$i]['url']; ?>" class="img-fluid" alt="Project">
									</a>
									<div class="mg-thumb-options">
										<div class="mg-zoom"><i class="fas fa-search"></i></div>
										<div class="mg-toolbar">
											<div class="mg-option checkbox-custom checkbox-inline">

												<?php echo $popups[$i]['site_url']; ?>

											</div>
											<div class="mg-group float-right">
												<a onclick="delete_essential(<?php echo $popups[$i]['id']; ?>)"><span
															class="uk-margin-small-right" uk-icon="trash"></span><i class="fa fa-trash" aria-hidden="true"></i></a>

											</div>
										</div>
									</div>
								</div>
								<h5 class="mg-title font-weight-semibold"><?php echo $popups[$i]['title']; ?></h5>
								<div class="mg-description">
									<small class="float-left text-muted"><?php if($popups[$i]['city'] != "NULL"){?><?php echo $popups[$i]['city']; ?> <?php }?>

										<?php echo $popups[$i]['state']; ?>
										</small>
									<small class="float-right text-muted"><?php if ($popups[$i]['brandcode'] == 'PCT0000001') { ?>
											Fabricspa
										<?php } else if ($popups[$i]['brandcode'] == 'PCT0000014'){ ?>
											Click2Wash
										<?php } else{}?></small>
								</div>
							</div>
						</div>
						<?PHP } ?>
					</div>
				</div>
			</div>
		</section>
	</section>
</div>


<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom/admin.css"/>







<script>
	<?php for($j=0;$j<=sizeof($states);$j++){?>
	$('#<?php echo $j;?>').hide();
	<?php }?>
	$('#default').click(function(){
		if($(this).prop("checked") == true){
			$('#state').hide();
			$('#state').val("");
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
			<?php }?>
		}
		else if($(this).prop("checked") == false){
			$('#state').show();
			if($('#state') != "")
				$("#state").change();
		}
	});
	$("#state").change(function(){
		$('input[type="checkbox"]').prop("checked", false);
		$('#cities').val("");
		var statecode = $('#state').val();
		if(statecode != "all"){
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/console_controller/get_state_cities_sp",
				dataType: 'json',
				data: {
					statecode: statecode
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							//progressElem.value+=Math.round(percentComplete * 100);
						}
					}, false);
					return xhr;
				},
				beforeSend: function () {
					// $.blockUI({

					//     message: '<h1>Please wait...</h1>'

					// });
				},
				complete: function () {
					$.unblockUI();
				},
				success: function (res) {
					var row = res.row;
					<?php $j='';

					for($j=0;$j<=sizeof($states);$j++){?>

					if(<?php echo $j;?> == row+1){
						$('#<?php echo $j;?>').show();
					}else{

						$('#<?php echo $j;?>').hide();
					}
					<?php } ?>
					$('#0').hide();

				}
			});
		}else{
			<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
			<?php }?>
		}
	});

	$('.editable').on('blur keyup paste', function () {
		var offer_img = $(this).parent().prev().children()[0];
		//var next_children=$(this).parent().next().children();
		var popup_id = $(offer_img).attr('popup_id');
		if ($('#' + popup_id + '_save').hasClass('uk-hidden')) {
			$('#' + popup_id + '_save').removeClass('uk-hidden');
		}

	});

	$('.editable_expiry').on('blur keyup paste', function () {
		var offer_img = $(this).parent().parent().prev().prev().children()[0];

		//var next_children=$(this).parent().next().children();
		var popup_id = $(offer_img).attr('popup_id');

		if ($('#' + popup_id + '_save').hasClass('uk-hidden')) {
			$('#' + popup_id + '_save').removeClass('uk-hidden');
		}

	});

	$('.editable_brand').change(function () {
		var offer_img = $(this).parent().parent().prev().prev().children()[0];

		//var next_children=$(this).parent().next().children();
		var popup_id = $(offer_img).attr('popup_id');

		if ($('#' + popup_id + '_save').hasClass('uk-hidden')) {
			$('#' + popup_id + '_save').removeClass('uk-hidden');
		}
	})

	function delete_essential(id) {


		UIkit.modal.confirm('Do you want to delete this banner?').then(function () {
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/console_controller/delete_essentialpopups",
				dataType: 'json',
				data: {
					popup_id: id
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							progressElem.value += Math.round(percentComplete * 100);
						}
					}, false);
					return xhr;
				},
				beforeSend: function () {
					$.blockUI({

						message: '<h1>Please wait...</h1>'

					});
				},
				complete: function () {
					$.unblockUI();
				},
				success: function (res) {
					if (res.status == 'success') {
						UIkit.notification({
							message: 'Successfully deleted',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});

						$('#' + id + '_card').parent().remove();
					} else {
						UIkit.notification({
							message: 'Failed to delete',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				}
			});
		}, function () {

		});


	}

	function save_changes(id) {
		var title = $('#' + id + '_title').text();
		var site_url = $('#' + id + '_site_url').text();
		jQuery.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "index.php/console_controller/save_essentialpopup",
			dataType: 'json',
			data: {
				popup_id: id,
				title: title,

			},
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				//Download progress
				xhr.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						progressElem.value += Math.round(percentComplete * 100);
					}
				}, false);
				return xhr;
			},
			beforeSend: function () {
				$.blockUI({

					message: '<h1>Please wait...</h1>'

				});
			},
			complete: function () {
				$.unblockUI();
			},
			success: function (res) {

				if (res.status == 'success') {
					UIkit.notification({
						message: 'Successfully saved',
						status: 'success',
						pos: 'bottom-center',
						timeout: 1000
					});
				} else {
					UIkit.notification({
						message: 'Failed to save',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}


			}
		});
	}

	UIkit.upload('.js-upload', {
		url: base_url + 'index.php/console_controller/upload_image',
		multiple: false,
		concurrent: 1,
		beforeSend: function () {
			////console.log('beforeSend', arguments);
		},
		beforeAll: function () {
			////console.log('beforeAll', arguments);
		},
		load: function () {
			////console.log('load', arguments);
		},
		error: function () {
			//console.log('error', arguments);
		},
		complete: function () {
			////console.log('complete', arguments);
		},

		loadStart: function (e) {
			////console.log('loadStart', arguments);
			/*
			 bar.removeAttribute('hidden');
			 bar.max = e.total;
			 bar.value = e.loaded;*/
		},

		progress: function (e) {
			////console.log('progress', arguments);

			/* bar.max = e.total;
			 bar.value = e.loaded;*/
		},

		loadEnd: function (e) {
			////console.log('loadEnd', arguments);

			/* bar.max = e.total;
			 bar.value = e.loaded;*/
		},

		completeAll: function (res) {

			var response = JSON.parse(res.response);
			if(response.status =='success'){
				if (response.moved_files.length > 0) {
					$('#new_offer_img_block').html('');
					var img = $('<img />').attr({
						'id': 'new_offer_image',
						'src': response.moved_files[0].link

					}).appendTo('#new_offer_img_block');

				} else if (response.existing_files.length > 0) {
					$('#new_offer_img_block').html('');
					var img = $('<img />').attr({
						'id': 'new_offer_image',
						'src': response.existing_files[0].link

					}).appendTo('#new_offer_img_block');

				} else {
					UIkit.notification({
						message: response.failed_files[0].file + ' upload failed!',
						status: 'danger',
						pos: 'top-right',
						timeout: 5000

					});
				}
			}else{
				UIkit.notification({
					message: response.message,
					status: 'danger',
					pos: 'top-right',
					timeout: 5000

				});
			}




		}
	});

	function add_essentialpopups() {
		//console.log($('input[name="cities"]:checked').serialize());

		var title = $('#new_title').text();
		var site_url = $('#new_site_url').text();
		if(site_url == 'Site URL ')
			site_url = "";
		var essential_image = $('#new_offer_image').attr('src');
		if(typeof essential_image == 'undefined'){
			UIkit.notification({
				message: 'Please upload a banner',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
			return false;
		}
		var brand = $('#new_brand').val();
		if($('#default').is(":checked")){
			var state ="";
			var cities ="NULL";
		}else{
			var state = $('#state').val();
			if(state != "all" && state != ""){
				var cities=[];
				$(':checkbox:checked').each(function(i){
					cities[i] = $(this).val();
				});
			}else{
				var cities = "NULL";
			}
			if(state == "" || cities == ""){
				UIkit.notification({
					message: 'Please choose cities',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
				return false;
			}

		}
		UIkit.modal.confirm('Do you want to add this banner?').then(function () {
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "index.php/console_controller/add_essentialpopups",
				dataType: 'json',
				data: {
					title: title,
					essential_image: essential_image,
					site_url:site_url,
					state: state,
					cities: cities,
					brand:brand
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							progressElem.value += Math.round(percentComplete * 100);
						}
					}, false);
					return xhr;
				},
				beforeSend: function () {
					$.blockUI({

						message: '<h1>Please wait...</h1>'

					});
				},
				complete: function () {
					$.unblockUI();
				},
				success: function (res) {
					if (res.status == 'success') {
						UIkit.notification({
							message: 'Successfully saved',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});

						setTimeout(function () {
							location.reload();
						}, 1500);

					} else {
						UIkit.notification({
							message: 'Failed to save',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}


				}
			});
		})

	}


</script>
<!-- Examples -->
<!-- Vendor -->
<script src="<?php echo base_url(); ?>assets/newui/vendor/magnific-popup/jquery.magnific-popup.js"></script>

<!-- Examples -->
<script src="<?php echo base_url(); ?>assets/newui/js/examples/examples.mediagallery.js"></script>
