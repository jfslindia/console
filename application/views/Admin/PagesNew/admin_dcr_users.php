<style>
	ul.checkbox li { 
  border: 1px transparent solid; 
  display:inline-block;
  width:10em;
}
tr{
	height: auto;

}
.center-sign{
    width: 40% !important;
}
.blockUI{
	z-index: 2000 !important;
}
</style>
<div class="inner-wrapper">
	<section class="body-coupon">
		<header class="page-header">
			<h2>Fab Daily Users</h2>

			<div class="right-wrapper text-right  mr-5">
				<ol class="breadcrumbs">
                    <li>
                        <a href="<?php echo base_url(); ?>console/home">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li><span><a href="<?php echo base_url(); ?>consoleadmin/dcr_reports"><h2>Reports</h2></a></span></li>
                </ol>
			</div>
		</header>

		<div class="row">
			<div class="col-lg-4">
				<div class="center-sign" >
					<div class="panel card-sign">
                        <div class="card-title-sign mt-3 text-right">
							<h5 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i>
								Add a User</h5>
						</div>
						<div class="card-body">
							<form class="">
                            <div class="form-group mb-0">
                                <label class="" for="form-horizontal-text">Name :</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group mb-0">
                                <label class="" for="form-horizontal-text">Contact Number :</label>
                                <input type="tel" name="phone_no" id="phone_no" maxlength="10" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label class="" for="form-horizontal-text">Store Access:</label>
                                    <select class="form-control" id="store_access">
                                        <option value="0">From Anywhere</option>
                                        <option value="1">Within 100 meters from Store</option>
                                    </select>
                            </div>
                              <div class="form-group mb-0">
									<label class="" for="form-horizontal-text">Associated Branches :</label>
									<input type="text" name="branch_list"  id="branch_list" placeholder="Click here to show" id="filter_branches" class="form-control" onclick ="show_branch_list()" readonly>
								</div>
								<div class="form-group mb-0" id="choosed_branch_list" style="display:none;">
									<ul>
									<?php for ($i = 0; $i < sizeof($stores); $i++) { ?>
										<li id="<?php echo $stores[$i]['BranchCode'];?>"><?php echo $stores[$i]['BranchName'];?></li>
									<?php } ?>
									</ul>
								</div>
							<div class="row">
								<div class="col-md-4">
									<div class="" id="add_coupon">
										<button id="add_coupon" type="button"
											class="btn btn-primary mt-4" onclick="save_dcr_user('add')">
										ADD USER
										</button>
									</div>
								</div>
								
							</div>
					</form>
						</div>

					     </div>
				    </div>
			</div>

	<div class="col-lg-8 ">
	<div class="mt-5 mb-5">

		<table id="dcr_users" 
			   class="table table-responsive-lg table-bordered table-striped table-sm mb-0">
			<thead>
			<tr>									
				<th>SI No</th>
				<th>Name</th>
				<th>PhoneNumber</th>
				<th style="min-width: 100px;">Store Access From</th>
				<th>Stores</th>
				<th>Store Names</th>
				<th style="min-width: 120px;">CreatedOn</th>
				<th>CreatedBy</th>
				<th style="min-width: 120px;">ModifiedOn</th>
				<th>ModifiedBy</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			</thead>
		</table>
	</div>



</section>
</div>
<div id="show_branch_list" class="modal fade" role="dialog" style='overflow: auto'>
	<div class="modal-dialog">
		<div class="modal-content" style="width:800px;">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i>Stores List</h3>
			</div>
			<div class="modal-body">
					<div class="form-group mb-0" id="store_filter_block">
						<div class="uk-form-controls">
							<label class="" for="form-horizontal-text">Select  :</label>
							<label><input class="uk-radio" type="radio" name="store_filter" id="store_filter" value="all" > All Stores</label>
                        	<label><input class="uk-radio" type="radio" name="store_filter" id="store_filter" value="active_stores"> All Active Stores </label>
							<label><input class="uk-radio" type="radio" name="store_filter" id="store_filter" value="inactive_stores"> All Inactive Stores </label>
						</div>
					</div></br>
					<div class="form-group mb-3">
						<label class="" for="form-horizontal-text">Choose a State:</label>
							<select class="form-control" id="states">
								<option value="">Select a State</option>
								<?php for ($i = 0; $i < sizeof($states); $i++) { ?>
									<option value="<?php echo $states[$i]['statecode']; ?>" ><?php echo $states[$i]['statename']; ?></option>
								<?php } ?>
							</select>
					</div>
					<div class="form-group mb-3"  id="city_list">
						<label class="" for="form-horizontal-text" id="choose_city_filter"></label>
						<?php for ($i = 0; $i < sizeof($states); $i++) { ?>
							<div id="<?php echo $i; ?>">
								<ul class="checkbox" id="city_list">
									<?php for ($j = 0; $j < sizeof($cities[$i]); $j++) { ?>
										<li><input type="checkbox" class="city_names" name="cities" 
												id="cities" value="<?php echo $cities[$i][$j]['citycode']; ?>"
												> <?php echo $cities[$i][$j]['cityname']; ?></li>
									<?php } ?>
								<ul>
							</div>
						<?php } ?>
					</div>
					<div class="form-group mb-0" id="city_filter_block" style="display:none;">
						<label class="" for="form-horizontal-text">Select  Cities :</label>
					</div>
					<div class="form-group mb-0" id="all_branch_block">
						<label class="" for="form-horizontal-text">Store List :</label>
						<div id="stores_block" class="uk-margin uk-grid-small uk-child-width-1-2@m uk-grid">
							<div class="uk-inline" style="width:700px;margin-bottom:10px;">
								<a class="uk-form-icon" href="#" uk-icon="icon: search"></a>
								<input class="uk-input " placeholder="Search here..." type="text"  id="filter_text">
							</div>
							<?php for($i=0;$i<sizeof($active_stores);$i++){ ?>

								<label for="active_list"><input class="uk-checkbox active_branches" type="checkbox" name="selected_stores" id="<?php echo $active_stores[$i]['BranchCode'];?>" value="<?php echo $active_stores[$i]['BranchCode'];?>" ><?php echo $active_stores[$i]['BranchName'];?></label>
							<?php } ?>
							<?php for($i=0;$i<sizeof($inactive_stores);$i++){ ?>
								<label for="inactive_list"><input class="uk-checkbox inactive_branches" type="checkbox" name="selected_stores" id="<?php echo $inactive_stores[$i]['BranchCode'];?>" value="<?php echo $inactive_stores[$i]['BranchCode'];?>" >INACT <?php echo $inactive_stores[$i]['BranchName'];?></label>
							<?php } ?>
						</div>
						<div id="citywise_stores_block" class="uk-margin uk-grid-small uk-child-width-1-2@m uk-grid">
						</div>
					</div>
			</div>
			<div class="modal-footer" id="branch_list_btn">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-primary" onclick="check_branch_checked()">Ok</button>
			</div>
		</div>
	</div>
</div>
<div id="show_user_details_modal" class="modal fade" role="dialog" style="">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i> User Details</h3>
			</div>
			<div class="modal-body">
				<form class=""  id="submitApprove"  method="post">
					<div class="form-group mb-0" id="editname_date_block">
						<label class="" for="form-horizontal-text">Name :</label>
						<input type="text"  name="editname" id="editname"
							   class="form-control">

					</div>
					<div class="form-group mb-0" id="edit_contactno_block">
						<label class="" for="form-horizontal-text">Contact Number:</label>
						<input type="tel" maxlength="10" name="edit_contactno" id="edit_contactno"
							   class="form-control" readonly>

					</div>
					<div class="form-group mb-0" id="editexpiry_date_block">
						<label class="" for="form-horizontal-text">Store Access:</label>
						<select class="form-control" id="edit_store_access">
							<option value="0">From Anywhere</option>
							<option value="1">Within 100 meters from Store</option>
						</select>

					</div>

				</form>
			</div>
			<div class="modal-footer" id="dcr_user_details_btn">
				<button type="button" id="update_user_details" class="btn btn-primary" ><i class="fa fa-times"></i> UPDATE DETAILS</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_access_history" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content" style="width:500px;">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i>Change Access</h3>
			</div>
			<div class="modal-body">
			Store Access : </br>
            <!-- <div class="uk-inline"> -->
			<select class="form-control" id="datewise_store_access" style="width:450px;">
				<option value="">Choose Store Access</option>
				<option value="0">From Anywhere</option>
				<option value="1">Within 100 meters from Store</option>
			</select>
            <!-- </div> -->
			</br>
			Choose a date range : 
			<div>
                <input class="uk-input " name="access_start_date" id="access_start_date" type="date" min="<?= date('Y-m-d'); ?>" style="width:450px;"></br>
                <input class="uk-input " name="access_end_date" id="access_end_date" type="date" min="<?= date('Y-m-d'); ?>" style="width:450px;">
			</div>
            </br>
			<div  id="save_access_btn"></div>
			</div>
			<div class="uk-inline" style="padding-left:150px;" id="history_link"></div>
			<div class="uk-inline" id="access_history"></div>
			<div class="modal-footer" id="branch_list_btn">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<div id="show_accessable_stores" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content" style="width:500px;">
			<div class="modal-header">
				<h3 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc;"><i class="fa fa-folder"></i>Accessable Stores</h3>
			</div>
			<div class="modal-body">
			<div class="uk-inline" style="padding-left:320px;" id="add_store_link"></div></br>
			<div class="uk-inline" id="accessable_stores"></div>
			<div class="modal-footer" id="stores_list_btn">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
		$("#phone_no").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		$("#edit_contactno").keypress(function(e){
            var keyCode = e.which;
            if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) { 
            return false;
            }
        });
		<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
		<?php }?>
	});
    function show_branch_list()
     {
		if($('#name').val() != "" && $('#phone_no').val() != "" && $('#store_access').val() != "" && $('#pswd').val() != ""){
			$('#filter_text').val("");
			let branch_list = document.querySelectorAll('#stores_block label')
			$('input[type="checkbox"]').prop("checked", false);
			$('input[type="radio"]').prop("checked", false);
			$('#states').val("");
			for (let i = 0; i < branch_list.length; i++) {
				branch_list[i].style.display = "block";
			}
			var html ='<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>';
			html += '<button type="button" class="btn btn-primary" onclick="check_branch_checked()">Ok</button>';
			$('#branch_list_btn').html(html);
			$('#show_branch_list').modal({backdrop: 'static', keyboard: true, show: true});
			$("#show_branch_list").modal('show');
			$('#city_list').hide();
			<?php for($j=0;$j< sizeof($states);$j++){?>
				$('#<?php echo $j;?>').hide();
			<?php }?>
			$('#citywise_stores_block').hide();
			$('#stores_block').show();	
		}else{
			UIkit.notification({
				message: 'Please add other data',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}
    }
    $("#filter_text").on("keyup", function (){
		// var selected_stores = $('input[name="selected_stores"]:checked');
		// var selected_store_codes = [];
		// var selected_store_names = [];
		// selected_stores.each(function () {
		// 	selected_store_codes[i] = $(this).val();
		// 	selected_store_names[i] = $(this).parent().text();
		// 	i++;
		// })
        var value = $(this).val().toLowerCase();
		let branch_list = document.querySelectorAll('#stores_block label')
		for (let i = 0; i < branch_list.length; i++) {
			checkedValue = branch_list[i].textContent || branch_list[i].innerText;
			if(checkedValue.toLowerCase().indexOf(value) > -1){
				branch_list[i].style.display = "";
			}else{
				branch_list[i].style.display = "none";
				// for(let j=0;j<selected_store_names.length;j++){
				// 	if(selected_store_names[j] == branch_list[i].textContent)
				// 		$('#'+selected_store_codes[j]).prop('checked',false);
				// }
			}
		}
    });
	function filter_citywise_stores()
	{
		
        var value = $('#citywise_stores').val().toLowerCase();
		let branch_list = document.querySelectorAll('#citywise_stores_block label')
		for (let i = 0; i < branch_list.length; i++) {
			checkedValue = branch_list[i].textContent || branch_list[i].innerText;
			if(checkedValue.toLowerCase().indexOf(value) > -1){
				branch_list[i].style.display = "";
			}else{
				branch_list[i].style.display = "none";
				
			}
		}
    }
	function check_branch_checked()
	{
		var branches = [];
		var html ="";
		$(':checkbox:checked').each(function(i){
            branches[i] = $(this).val();
        });
		if(branches.length> 0){
			$("#show_branch_list").modal('hide');
			let stores_list = document.querySelectorAll('#choosed_branch_list li');
			for (let i = 0; i < stores_list.length; i++) {
				stores_value = stores_list[i].textContent || stores_list[i].innerText;
				for(let j=0; j <branches.length; j++){
					if(stores_value == branches[j])
						$('#'+branches[j]).css("display:block;");
					else
						$('#'+branches[j]).css("display:none;");
				}
			}
			$('#choosed_branch_list').css('display:block;');
			save_dcr_user("add");
		}else{
			alert('Please choose branch');
		}

	}
	jQuery(document).ready(function () {
		var table = $('#dcr_users').DataTable({
				"bDestroy": true,
			fixedHeader: {
					header: false,
					footer: false
				},
				scrollX: true,
				autoWidth: false,
			    "order": [[ 0, "asc" ]],
				dom: 'Bfrtip', bInfo : false,
				"buttons": [
					'copy', 'csv', 'excel',
				],
				buttons: [
							{ extend: 'copy', className: 'btn btn-primary glyphicon glyphicon-duplicate', exportOptions: {
									columns: [0,1,2,3,6,7,8,9,11]
								}
							},
							{ extend: 'csv', className: 'btn btn-primary glyphicon glyphicon-save-file', exportOptions: {
									columns: [0,1,2,3,6,7,8,9,11]
								}
							},
							{ extend: 'excel', className: 'btn btn-primary glyphicon glyphicon-list-alt', exportOptions: {
									columns: [0,1,2,3,6,7,8,9,11]
								}
							}
						],
				'ajax': {
					'url': base_url + "consoleadmin_controller/get_dcr_users"
				},
				"columnDefs": [
					 {"searchable": true, "targets": [1,2,3,6]}
				],

				'columns': [
					{data: 'SI_no'},
					{data: 'Name'},
					{data: 'Phone'},
					{
						data: 'store_access',
						"render": function (data, type, row) {
							html ='<a href="#" onclick="change_access('+row['Id']+')"  title="Change datewise access" ><i class="fas fa-calendar-alt" aria-hidden="true"></i></a>' +' '+row['store_access'];
							return html;
						}
					},
					{data: 'BranchNames',visible:false},
					{
						data: 'Details',
						"render": function (data, type, row) {
							html = '<a href="#" onclick="show_accessable_branches('+row['Id']+')"  title="Accessable Stores list" ><i class="fa fa-bars" aria-hidden="true"></i></a>';
							return html;
						}
					},
					{data: 'Date'},
					{data: 'CreatedBy'},
					{
						data: 'ModifiedOn',
						"render": function (data, type, row) {
							if(row['ModifiedOn'] != "")
								var html = row['ModifiedOn'];
							else
								var html = '';
							return html;
						}
					},
					{
						data: 'ModifiedBy',
						"render": function (data, type, row) {
							if(row['ModifiedBy'] != "")
								var html = row['ModifiedBy'];
							else
								var html = '';
							return html;
						}
					},
					{
						data: 'Edit',
						"render": function (data, type, row) {
							var url= base_url +"consoleadmin_controller/edit_dcr_users/"+row['Id'];
							var html='<a href="#" onclick="show_dcr_user_modal('+row['Id']+')" class="btn btn-xs btn-primary" title="Edit"><i class="fas fa-pen-alt"></i></a>';
							return html;
						}
					},
					{
						title: ' Status',
						"render": function (data, type, row) {
							// if(row['user_status'] == 0){
							// 	html='<a href="#" onclick="delete_fabdaily_user('+row['Id']+')"  class="btn btn-xs btn-danger" style="color:#ff0000;background:#ffffff;">Delete<i class="fas fa-trash-alt"></i></a>';
							// }else{
								if(row['is_active']== 1)
									html='<a href="#" onclick="change_fabdaily_user_status('+row['Id']+')"  class="btn btn-xs btn-success">Active <i class="fas fa-toggle-on"></i></a>';
								else
									html='<a href="#" onclick="change_fabdaily_user_status('+row['Id']+')"  class="btn btn-xs btn-danger" >Inactive <i class="fas fa-toggle-off"></i></a>';
							// }
							return html;
						}
					},
					{
						data: 'Status',
						"render": function (data, type, row) {
							if(row['is_active']== 1)
								html ='Active';
							else
								html ='Inactive';
							return html;
						},
						visible:false
					}

				],
			})
		;

	});
	function save_dcr_user(mode){
		var user_branches = [];
		var user_branch_names = [];
		var i =0;
		var selected_stores = $('input[name="selected_stores"]:checked');
		selected_stores.each(function () {
			user_branches[i] = $(this).val();
			user_branch_names[i] = $(this).parent().text();
			i++;
		})
		if($('#phone_no').val() != "" && $('#phone_no').val().length != 10){
			UIkit.notification({
					message: 'Please add mobile number correctly',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
		}else{
			if($('#name').val() != "" && $('#phone_no').val() != "" && $('#store_access').val() != "" && user_branches.length > 0 && user_branch_names.length > 0){
				jQuery.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>" + "consoleadmin_controller/save_dcr_user",
					dataType: 'json',
					data: {
						mode:mode,
						name:$('#name').val(),
						contactno:$('#phone_no').val(),
						stores: user_branches,
						store_names: user_branch_names,
						store_access:$('#store_access').val()
					},
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						//Download progress
						xhr.addEventListener("progress", function (evt) {
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
								message: "Saved Successfully",
								status: 'success',
								pos: 'bottom-center',
								timeout: 1000
							});
							setTimeout(function () {
								location.reload();
							}, 1500);
						} else {
							UIkit.notification({
								message: res.message,
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
						}

					}
				});
			}else{
				UIkit.notification({
					message: 'Please add all data',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
			}
		}
	}
	function  show_accessable_branches(id){
		$.ajax({
			url :  base_url +"consoleadmin_controller/show_accessable_branches/"+id,
			data:{
				user_type:"DCR"
			},
			method:'POST',
			dataType:'json',
			success:function(response) {
				if(response.branches.length > 0){
					var html ='<table  class="table table-responsive-lg table-bordered table-striped table-sm mb-0" style="width:430px">';
					html +='<tr>';
					html += '<th></th>';
					html +='<th>Store Name</th>';
					html +='<th>CreatedOn</th>';
					html +='<th>CreatedBy</th>';
					html +='<th><a href="#" title="Remove Stores"  onclick="remove_branches('+id+')"  class="btn btn-xs btn-danger" style="color:#ff0000;background:none;border:none;"><i class="fas fa-trash-alt"></i></a></th>';
					html +='</tr>';
					html +='<tr>';
					html +='<td><label class="all_stores"><input class="uk-checkbox" type="checkbox" name="select_all_stores" id="select_all_stores" check value="1" onclick="select_all_stores()"></label></td>';
					html += '<th width="280px;">Select All</th>';
					html +='</tr>';
					for(i=0;i<response.branches.length;i++){
						html += '<tr>';
						html +='<td><label><input class="uk-checkbox" type="checkbox" name="unselected_stores" id="'+response.branches[i].BranchCode+'" value="'+response.branches[i].BranchCode+'"></label></td>';
						html += '<td>'+ response.branches[i]['BranchName'] +'</td>';
						if(response.branches[i]['CreatedOn'] != null)
							html += '<td>'+ response.branches[i]['CreatedOn'] +'</td>';
						else
							html += '';
						if(response.branches[i]['CreatedBy'] != null)
							html += '<td>'+ response.branches[i]['CreatedBy'] +'</td>';
						else
							html += '';
						html += '<td></td>';
						html += '</tr>';
					}
					html += '</table>';
				}else{
					var html = '';
				}
				$('#accessable_stores').html(html);
				$('#add_store_link').html('<a href="#" title="Remove Stores"onclick="add_new_stores('+id+')" style="color:#000000"><i class="fa fa-plus" aria-hidden="true" style="color:#000000"></i><b>Add Stores</b></a>');
				// var html ='<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="download_stores_list('+id+')"><i class="fa fa-download" aria-hidden="true"></i>Download</button>';
				// html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>';
				// $('#stores_list_btn').html(html);
				$('#show_accessable_stores').modal({backdrop: 'static', keyboard: true, show: true});
				$("#show_accessable_stores").modal('show');	

			}
		});
	}
	function update_branch_details(id)
	{
		var user_branches = [];
		var user_branch_names = [];
		var html ="";
		$(':checkbox:checked').each(function(i){
			user_branches.push($(this).val());
            user_branch_names.push($(this).parent().text());
		});
		if(user_branches.length> 0){
			$("#show_branch_list").modal('hide');
			let stores_list = document.querySelectorAll('#choosed_branch_list li');
			for (let i = 0; i < stores_list.length; i++) {
				stores_value = stores_list[i].textContent || stores_list[i].innerText;
				for(let j=0; j <user_branches.length; j++){
					if(stores_value == user_branches[j])
						$('#'+user_branches[j]).css("display:block;");
					else
						$('#'+user_branches[j]).css("display:none;");
				}
			}
			update_dcr_user(id,"","",user_branches,user_branch_names,"");
		}else{
			alert('Please choose branch');
		}

	}
	function update_dcr_user(id,name,phone_no,user_branches,user_branch_names,store_access)
	{
		jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "consoleadmin_controller/save_dcr_user",
				dataType: 'json',
				data: {
					mode:"edit",
					name:name,
					contactno:phone_no,
					stores: user_branches,
					store_names: user_branch_names,
					store_access: store_access,
					id: id
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
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
							message: "Updated Successfully",
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});
						setTimeout(function () {
							location.reload();
						}, 1500);
					} else {
						UIkit.notification({
							message: res.message,
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}

				}
			});
	}
	function  show_dcr_user_modal(id){
		$.ajax({
			url :  base_url +"consoleadmin_controller/get_fabdaily_user_details/"+id,
			data:{
				user_type : "DCR"
			},
			method:'POST',
			dataType:'json',
			success:function(response) {
				$('#editname').val(response.user_details.Name);
				$('#edit_contactno').val(response.user_details.Phone);
				$('#edit_store_access').val(response.user_details.store_access_limit);
				var html = '<button type="button" id="update_user_details" onclick="update_user_details('+response.user_details.Id+')" class="btn btn-primary" ><i class="fa fa-times"></i> UPDATE DETAILS</button>';
				html += '<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>';
				$('#dcr_user_details_btn').html(html);
				$('#show_user_details_modal').modal({backdrop: 'static', keyboard: true, show: true});
			}
		});
		$("#show_user_details_modal").modal('show');
	}
	function update_user_details(id)
	{
		if($('#editname').val() != "" && $('#edit_contactno').val() != ""){
			update_dcr_user(id,$('#editname').val(),$('#edit_contactno').val(),"","",$('#edit_store_access').val());
		}else{
			alert("Please fill all details");
		}
	}
	function change_fabdaily_user_status(id){
		UIkit.modal.confirm("Do you want to change the status?").then(function () {
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/change_fabdaily_user_status",
				dataType: 'json',
				data: {
					id:id
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
					$('#loading').show();
					$.blockUI({

						message: '<h1>Please wait...</h1>'

					});
					// $.blockUI();
					// setTimeout(unBlockUI, 5000);

				},
				complete: function () {
					$.unblockUI();

				},

				success: function (res) {

					if (res.status == 'Successfully Updated') {
						UIkit.notification({
							message: 'Successfully Updated',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});

						setTimeout(function () {
							location.reload();
						}, 1500);

					}
					else {
						UIkit.notification({
							message: 'No Data Found',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				},

				error: function (res) {
					UIkit.notification({
						message: 'Updation Failed',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
			});
		})
	}
	function change_access(id)
	{
		$('#datewise_store_access').val("");
		$('#access_date').val("");
		$('#history_link').html('<a href="#" onclick="show_history('+id+')">Click here to see history</a>')
		$('#save_access_btn').html('<button type="button" class="btn btn-primary" onclick="save_datewise_access('+id+')" style="width:450px;">Save</button>')
		$('#access_history').html('')
		$('#show_access_history').modal({backdrop: 'static', keyboard: true, show: true});
		$("#show_access_history").modal('show');	
	}
	function save_datewise_access(id)
	{
		var access = $('#datewise_store_access').val();
		var start_date = $('#access_start_date').val();
		var end_date = $('#access_end_date').val();
		if(access != "" && start_date != "" && end_date != ""){
			if(start_date > end_date){
				UIkit.notification({
					message: 'Please choose dates correctly',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
			}else{
				jQuery.ajax({
					type: "POST",
					url: base_url + "consoleadmin_controller/check_datewise_access",
					dataType: 'json',
					data: {
						id:id,
						access:access,
						start_date:start_date,
						end_date:end_date
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
						$('#loading').show();
						$.blockUI({

							message: '<h1>Please wait...</h1>'

						});
						// $.blockUI();
						// setTimeout(unBlockUI, 5000);

					},
					complete: function () {
						$.unblockUI();
					},
					success: function (res) {
						if (res.status == 'success') {
							if(res.is_exist == 0){
								save_access_data(id,access,start_date,end_date,'');
							}else{
								alert('An access already given for this user including this date range,do you want to update it');
								save_access_data(id,access,start_date,end_date,res.existing_ids);
							}

						}else {
							UIkit.notification({
								message: 'No Data Found',
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
						}
					},

					error: function (res) {
						UIkit.notification({
							message: 'Failed to add',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				});
			}
		}else{
			UIkit.notification({
				message: 'Please add all data',
				status: 'danger',
				pos: 'bottom-center',
				timeout: 1000
			});
		}
	}
	function show_history(id)
	{
		jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/get_datewise_access",
				dataType: 'json',
				data: {
					"id":id
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
					$('#loading').show();
					$.blockUI({
						message: '<h1>Please wait...</h1>'
					});
				},
				complete: function () {
					$.unblockUI();
				},
				success: function (res) {
					if (res.status == 'success') {
						if(res.general_access == 1 || res.general_access == 0){
							var html = '<table style="width: 490px;" class="table table-responsive-lg table-striped table-sm mb-0">';
							html += '<th>General  Access :<th>';
							if(res.general_access == 0)
								html += '<th>From Anywhere</th>';
							else
								html += '<th>Within 100 meters from Store</th>';
							html += '</table>';
						}else{
							var html = '';
						}
						if(res.access_data.length > 0){
							html +='<table style="width: 490px;" class="table table-responsive-lg table-bordered table-striped table-sm mb-0">';
							html +='<tr>';
							html +='<th>From Date</th>';
							html +='<th>To Date</th>';
							html +='<th>Access</th>';
							html +='<th>CreatedOn</th>';
							html +='<th>CreatedBy</th>';
							html +='</tr>';
							for(i=0;i<res.access_data.length;i++){
								html += '<tr>';
								html += '<td>'+ res.access_data[i]['date'] +'</td>';
								if(res.access_data[i]['end_date'] != null)
									html += '<td>'+ res.access_data[i]['end_date'] +'</td>';
								else
									html += '<td></td>';
								if(res.access_data[i]['store_access'] == 0)
									html += '<td>From Anywhere</td>';
								else
									html += '<td>Within 100 meters from Store</td>'
								if(res.access_data[i]['CreatedOn'] != null)
									html += '<td>'+ res.access_data[i]['CreatedOn'] +'</td>';
								else
									html += '<td></td>';
								if(res.access_data[i]['CreatedBy'] != null)
									html += '<td>'+ res.access_data[i]['CreatedBy'] +'</td>';
								else
									html += '<td></td>';
								//if(res.access_data[i]['is_active'] == 1)
								html += '<td><a href="#" title="Remove" onclick="remove_access('+res.access_data[i]['Id']+','+id+')"><i class="fas fa-trash-alt"></i></a></td>';
								// else
								// 	html += '';
								html += '</tr>';
							}
							html += '</table>';
						}
						$('#access_history').html(html);
						$('#history_link').html("");
					}else {
						UIkit.notification({
							message: 'No Data Found',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				},

				error: function (res) {
					UIkit.notification({
						message: 'No Data Found',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
		});
	}
	function remove_access(id,user_id)
	{
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/remove_access",
				dataType: 'json',
				data: {
					id:id
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
					$('#loading').show();
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
							message: 'Successfully Removed',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});
						$('#history_link').html('<a href="#" onclick="show_history('+user_id+')">Click here to see history</a>');
						$('#access_history').html("");
						setTimeout(function () {
							location.reload();
						}, 1500);
					}
					else {
						UIkit.notification({
							message: 'No Data Found',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				},

				error: function (res) {
					UIkit.notification({
						message: 'Failed to remove',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
			});
		
	}
	function save_access_data(id,access,start_date,end_date,existing_ids)
	{
		jQuery.ajax({
			type: "POST",
			url: base_url + "consoleadmin_controller/save_datewise_access",
			dataType: 'json',
			data: {
				id:id,
				access:access,
				start_date:start_date,
				end_date:end_date,
				existing_ids:existing_ids
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
				$('#loading').show();
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
						message: 'Successfully Saved',
						status: 'success',
						pos: 'bottom-center',
						timeout: 1000
					});
					$('#datewise_store_access').val("");
					$('#access_start_date').val("");
					$('#access_end_date').val("");
					$('#access_date').val("");
					$('#history_link').html('<a href="#" onclick="show_history('+id+')">Click here to see history</a>');
					$('#access_history').html("");
					setTimeout(function () {
							location.reload();
					}, 1500);
				}
				else {
					UIkit.notification({
						message: 'No Data Found',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
			},
			error: function (res) {
				UIkit.notification({
					message: 'Updation Failed',
					status: 'danger',
					pos: 'bottom-center',
					timeout: 1000
				});
			}
		});
	}
	function delete_fabdaily_user(id)
	{
		UIkit.modal.confirm("Do you want to delete this user?").then(function () {
			jQuery.ajax({
				type: "POST",
				url: base_url + "consoleadmin_controller/delete_fabdaily_user",
				dataType: 'json',
				data: {
					id:id
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
					$('#loading').show();
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
							message: 'Deleted Successfully',
							status: 'success',
							pos: 'bottom-center',
							timeout: 1000
						});
						setTimeout(function () {
								location.reload();
						}, 1500);
					}
					else {
						UIkit.notification({
							message: 'Failed to delete',
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}
				},
				error: function (res) {
					UIkit.notification({
						message: 'Failed to delete',
						status: 'danger',
						pos: 'bottom-center',
						timeout: 1000
					});
				}
			});
		})
	}
	$('input[type=radio][name=store_filter]').change(function(){
			$('#filter_text').val("");
			$('#citywise_stores').val("");
			if($('#states').val() != ""){
				$('#states').val("");
				$('#all_branch_block').show();
				$('#citywise_stores_block').hide();
				$('#stores_block').show();
				$('#city_list').hide();
			} 
			$('.active_branches').prop('checked', false);
			$('.inactive_branches').prop('checked', false);  
            if ($('input[type=radio][name=store_filter]:checked').val() == 'all') {
				$('.active_branches').prop('checked', true);
				$('.inactive_branches').prop('checked', true);
				$('label[for="active_list"]').show();
				$('label[for="inactive_list"]').show();
			}else if ($('input[type=radio][name=store_filter]:checked').val() == 'active_stores') {
				$('.active_branches').prop('checked', true);
				$('label[for="active_list"]').show();
				$('label[for="inactive_list"]').hide();
				$('.inactive_branches').prop('checked', false);
			}else{
				$('.active_branches').prop('checked', false);
				$('label[for="active_list"]').hide();
				$('label[for="inactive_list"]').show();
				$('.inactive_branches').prop('checked', true);
			}
	});
	$('#states').change(function(){
		$('.active_branches').prop('checked', false);
		$('.inactive_branches').prop('checked', false);
		$("input[name='selected_stores']").prop("checked", false);
		$("input[name='cities']").prop("checked", false);
		$('#all_branch_block').hide();
		$('#citywise_stores_block').html("");
		if($('#states').val() != ""){
			jQuery.ajax({
					type: "POST",
					url: base_url + "consoleadmin_controller/get_state_cities_sp",
					dataType: 'json',
					data: {
						statecode: $('#states').val()
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
					beforeSend: function () {},
					complete: function () {
						$.unblockUI();
					},
					success: function (res) {
						$('#city_list').show();
						$('#choose_city_filter').html('<p>Select Cities: <input class="uk-input " placeholder="Search Cities here..." type="text" id="statewise_cities_filter" onkeyup="filter_cities();" style="width:700px;"></p>');
						var row = res.row;
						<?php $j='';
						for($j=0;$j<sizeof($states);$j++){?>
						if(<?php echo $j;?> == row){
							$('#<?php echo $j;?>').show();
						}else{
							$('#<?php echo $j;?>').hide();
						}
						<?php } ?>
					}
			});
		}else{
			$('#all_branch_block').show();
			$('#citywise_stores_block').hide();
			$('#stores_block').show();
			$('#city_list').hide();
			if($('input[type=radio][name=store_filter]:checked').val() == "all"){
				$('.active_branches').prop('checked', true);
				$('.inactive_branches').prop('checked', true);
			}else if($('input[type=radio][name=store_filter]:checked').val() == "active_stores"){
				$('.active_branches').prop('checked', true);
				$('.inactive_branches').prop('checked', false);
			}else if($('input[type=radio][name=store_filter]:checked').val() == "inactive_stores"){
				$('.active_branches').prop('checked', false);
				$('.inactive_branches').prop('checked', true);
			}
		}
	});
	$(".city_names").on("click",function(){
		var citycodes = [];
		if(!$(this).is(':checked')){
			var checked = 0;
			var city_code = $(this).val();
		}else{
			var checked = 1;
			var city_code = $(this).val();
		}
		var i =0;
		var selected_cities = $('input[name="cities"]:checked');
		selected_cities.each(function () {
			citycodes[i] = $(this).val();
			i++;
		})
		var city_count =  citycodes.length;
		if(city_count != 0){
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "consoleadmin_controller/get_citywise_branches",
				dataType: 'json',
				data: {
					city_code : city_code
				},
				xhr: function () {
					var xhr = new window.XMLHttpRequest();
					//Download progress
					xhr.addEventListener("progress", function (evt) {
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
						if ($('#citywise_stores_block').is(':empty'))
							var html ='<div class="uk-inline" style="width:700px;margin-bottom:10px;"><a class="uk-form-icon" href="#" uk-icon="icon: search"></a><input class="uk-input " placeholder="Search Stores here..." type="text"  id="citywise_stores" onkeyup="filter_citywise_stores();"></div>';
						else
							var html = '';
						if(checked == 1){
							if($('input[type=radio][name=store_filter]:checked').val() == undefined){
								for(var i=0;i<res.active_stores.length;i++){
									html += '<label id="'+res.active_stores[i].BRANCHCODE+'_checkbox"><input class="uk-checkbox active_branches" type="checkbox" name="selected_stores" id="'+res.active_stores[i].BRANCHCODE+'" value="'+res.active_stores[i].BRANCHCODE+'">'+res.active_stores[i].BRANCHNAME+'</label>';
								}
								for(var i=0;i<res.inactive_stores.length;i++){
									html += '<label id="'+res.inactive_stores[i].BRANCHCODE+'_checkbox"><input class="uk-checkbox inactive_branches" type="checkbox" name="selected_stores" id="'+res.inactive_stores[i].BRANCHCODE+'" value="'+res.inactive_stores[i].BRANCHCODE+'">INACT '+res.inactive_stores[i].BRANCHNAME+'</label>';
								}
							}
							if($('input[type=radio][name=store_filter]:checked').val() == "all"){
								for(var i=0;i<res.active_stores.length;i++){
									html += '<label id="'+res.active_stores[i].BRANCHCODE+'"><input class="uk-checkbox active_branches" type="checkbox" name="selected_stores" id="'+res.active_stores[i].BRANCHCODE+'" value="'+res.active_stores[i].BRANCHCODE+'" checked>'+res.active_stores[i].BRANCHNAME+'</label>';
								}
								for(var i=0;i<res.inactive_stores.length;i++){
									html += '<label id="'+res.active_stores[i].BRANCHCODE+'"><input class="uk-checkbox inactive_branches" type="checkbox" name="selected_stores" id="'+res.inactive_stores[i].BRANCHCODE+'" value="'+res.inactive_stores[i].BRANCHCODE+'" checked>INACT '+res.active_stores[i].BRANCHNAME+'</label>';
								}
							}else if($('input[type=radio][name=store_filter]:checked').val() == "inactive_stores"){
								if(res.inactive_stores.length > 0){
									for(var i=0;i<res.inactive_stores.length;i++){
										html += '<label id="'+res.active_stores[i].BRANCHCODE+'"><input class="uk-checkbox inactive_branches" type="checkbox" name="selected_stores" id="'+res.inactive_stores[i].BRANCHCODE+'" value="'+res.inactive_stores[i].BRANCHCODE+'" checked>INACT '+res.active_stores[i].BRANCHNAME+'</label>';
									}
								}else{
									$('input:checkbox[value="' + citycodes[city_count-1] + '"]').prop('checked', false);
									UIkit.notification({
										message: 'No Inactive stores found in this city',
										status: 'danger',
										pos: 'bottom-center',
										timeout: 1000
									});
								}
							}else if($('input[type=radio][name=store_filter]:checked').val() == "active_stores"){
								for(var i=0;i<res.active_stores.length;i++){
									html += '<label><input class="uk-checkbox active_branches" type="checkbox" name="selected_stores" id="'+res.active_stores[i].BRANCHCODE+'" value="'+res.active_stores[i].BRANCHCODE+'" checked>'+res.active_stores[i].BRANCHNAME+'</label>';
								}
							}
							$('#citywise_stores_block').append(html);	
							$('#citywise_stores_block').show();
							$('#stores_block').hide();		
							$('#all_branch_block').show();
						}else{
							for(var i=0;i<res.active_stores.length;i++){
								$('#'+res.active_stores[i].BRANCHCODE+'_checkbox').remove();
								$('input:checkbox[value="' +res.active_stores[i].BRANCHCODE+ '"]').prop('checked', false);
							}
							for(var i=0;i<res.inactive_stores.length;i++){
								$('#'+res.inactive_stores[i].BRANCHCODE+'_checkbox').remove();
								$('input:checkbox[value="' +res.inactive_stores[i].BRANCHCODE+ '"]').prop('checked', false);
							}
							//uncheck and hide the respective stores
						}
					} else {
						$('input:checkbox[value="' + city_code + '"]').prop('checked', false);
						UIkit.notification({
							message: res.message,
							status: 'danger',
							pos: 'bottom-center',
							timeout: 1000
						});
					}

				}
			});
		}else{
			$('#citywise_stores_block').html("");
		}
	});
	function add_new_stores(user_id)
     {
		$('input[type="checkbox"]').prop("checked", false);
		$('input[type="radio"]').prop("checked", false);
		$("#show_accessable_stores").modal('hide');	
		let branch_list = document.querySelectorAll('#stores_block label')
		$('input[type="checkbox"]').prop("checked", false);
		$('input[type="radio"]').prop("checked", false);
		$('#states').val("");
		for (let i = 0; i < branch_list.length; i++) {
			branch_list[i].style.display = "block";
		}
		var html ='<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="show_accessable_branches('+user_id+')">Back</button>';
		html += '<button type="button" class="btn btn-primary" onclick="update_accessable_stores('+user_id+')">Save</button>';
		$('#branch_list_btn').html(html);
		$('#show_branch_list').modal({backdrop: 'static', keyboard: true, show: true});
		$("#show_branch_list").modal('show');
		$('#city_list').hide();
		$('#all_branch_block').show();
		$('#citywise_stores_block').hide();
		$('#stores_block').show();	
		<?php for($j=0;$j< sizeof($states);$j++){?>
			$('#<?php echo $j;?>').hide();
		<?php }?>
		$('#citywise_stores_block').hide();
		$('#stores_block').show();	
    }
	function update_accessable_stores(id)
	{
		var user_branches = [];
		var user_branch_names = [];
		var i =0;
		var selected_stores = $('input[name="selected_stores"]:checked');
		selected_stores.each(function () {
			user_branches[i] = $(this).val();
			user_branch_names[i] = $(this).parent().text();
			i++;
		})
		if(user_branches.length > 0 && user_branch_names.length > 0){
			jQuery.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>" + "consoleadmin_controller/update_dcr_accessable_stores",
					dataType: 'json',
					data: {
						user_id: id,
						stores: user_branches,
						store_names: user_branch_names					
					},
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						//Download progress
						xhr.addEventListener("progress", function (evt) {
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
							alert("Updated Succesfully");
							setTimeout(function () {
								location.reload();
							}, 1500);
						} else {
							UIkit.notification({
								message: res.message,
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
						}

					}
				});
		}else{
			alert("Please Choose stores");
		}
	}
	function remove_branches(id)
	{
		var user_branches = [];
		var i =0;
		var unselected_stores = $('input[name="unselected_stores"]:checked');
		unselected_stores.each(function () {
			user_branches[i] = $(this).val();
			i++;
		})
		if(user_branches.length > 0){
			jQuery.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>" + "consoleadmin_controller/remove_dcr_store_access",
					dataType: 'json',
					data: {
						user_id: id,
						stores: user_branches
					},
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						//Download progress
						xhr.addEventListener("progress", function (evt) {
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
								message: "Removed Successfully",
								status: 'success',
								pos: 'bottom-center',
								timeout: ''
							});
							setTimeout(function () {
								location.reload();
							}, 1500);
						} else {
							UIkit.notification({
								message: "Failed to Remove",
								status: 'danger',
								pos: 'bottom-center',
								timeout: 1000
							});
						}

					}
				});
		}else{
			alert("Please Choose stores");
		}
	}
	function select_all_stores(){
		var citycodes = [];
		if(document.getElementById('select_all_stores').checked == true){
			$('input[name="unselected_stores"]').prop('checked',true);
		}else{
			$('input[name="unselected_stores"]').prop('checked',false);
		}
	}
	function filter_cities()
	{
		var value = $('#statewise_cities_filter').val().toLowerCase();
		let branch_list = document.querySelectorAll('#city_list li')
		for (let i = 0; i < branch_list.length; i++) {
			checkedValue = branch_list[i].textContent || branch_list[i].innerText;
			if(checkedValue.toLowerCase().indexOf(value) > -1){
				branch_list[i].style.display = "";
			}else{
				branch_list[i].style.display = "none";
				
			}
		}
	}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
