<style>
table {
  /* border-collapse: collapse; */
  width: 100%;
  
}
th {
  background-color: skyblue;
  color: white;
}
tr:nth-child(even){background-color: #f2f2f2}
th, td {
  text-align: left;
  padding: 16px;
}
body {
  font-family: Arial, sans-serif;
  background: url(http://www.shukatsu-note.com/wp-content/uploads/2014/12/computer-564136_1280.jpg) no-repeat;
  background-size: cover;
  height: 100vh;
}

h1 {
  text-align: center;
  font-family: Tahoma, Arial, sans-serif;
  color: #06D85F;
  margin: 80px 0;
}

.box {
  width: 40%;
  margin: 0 auto;
  background: rgba(255,255,255,0.2);
  padding: 35px;
  border: 2px solid #fff;
  border-radius: 20px/50px;
  background-clip: padding-box;
  text-align: center;
}

.button {
  font-size: 1em;
  padding: 10px;
  color: #fff;
  border: 2px solid #06D85F;
  border-radius: 20px/50px;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease-out;
}
.button:hover {
  background: #06D85F;
}

.overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0, 0, 0, 0.7);
  transition: opacity 500ms;
  visibility: hidden;
  opacity: 0;
}
.overlay:target {
  visibility: visible;
  opacity: 1;
}

.popup {
  margin: 70px auto;
  padding: 20px;
  background: #fff;
  border-radius: 5px;
  width: 30%;
  position: relative;
  transition: all 5s ease-in-out;
}

.popup h2 {
  margin-top: 0;
  color: #333;
  font-family: Tahoma, Arial, sans-serif;
}
.popup .close {
  position: absolute;
  top: 20px;
  right: 30px;
  transition: all 200ms;
  font-size: 30px;
  font-weight: bold;
  text-decoration: none;
  color: #333;
}
.popup .close:hover {
  color: #06D85F;
}
.popup .content {
  max-height: 30%;
  overflow: auto;
}
#pay_button{
  color: #ff0000;
}
</style>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
<?php if(ADMIN_PREVILIGE == 'root') {?>
  <a href="<?php echo site_url('Console_Controller/admin_fab_home');?>">Back To Rate Page</a><br><br>
<?php }?>
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Cart</h3>
         <div class="uk-grid" id="grid">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">OrderId</th>
                        <th scope="col">Service</th>
                        <th scope="col">CustomerDetails</th>
                        <th scope="col">Time Slot </th>
                        <th scope="col">Total Payable amount(Rs)</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Order Status</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n="1"; 
                         
                        for ($i = 0; $i < sizeof($cart); $i++)
                        {
                        ?>
                    <tr>
                        <td scope="row"><?php echo $n; ?></td>
                        <td><?php echo $cart[$i]['order_id']; ?></td>
                        <td><?php echo $cart[$i]['service_type']; ?></td>
                        <td><?php echo  $cart[$i]['customer_id'].'
                        ,'.$cart[$i]['name'].' , '.$cart[$i]['mobile_number']; ?></td>
                        <td><?php echo $cart[$i]['time_slot']." , ". date('d-m-Y',strtotime($cart[$i]['pick_up_date']))?></td>
                        <td><?php echo $cart[$i]['grand_total']; ?></td>
                        <td><?php echo $cart[$i]['cart_status']; ?></td>
                        <td id="status<?php echo $cart[$i]['cart_id'];?>"><?php echo $cart[$i]['status'];?></td>
                        <td><a href="#popup<?php echo $cart[$i]['cart_id'];?>"><button>Update Order Status</button></a></td>
                        <td><button type="button"><a href="<?php echo site_url('console/show_fabhome_cart_details/'.$cart[$i]['cart_id']);?>">View More</a></button></td>  
                         <?php
                        $newtime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -15 minutes"));
                         if(strtolower($cart[$i]['status']) != 'completed'  &&  strtolower($cart[$i]['status']) != 'cancelled'  && strtolower($cart[$i]['cart_status']) != 'paid' && $cart[$i]['cretn_date'] < $newtime){?>
                            <td><button type="button"id="pay_button" onclick="update_fabhome_payment_status(<?php echo $cart[$i]['cart_id'];?>)">Update Payment Status</button></td>
                        <?php }else{?>
                            <td></td>
                        <?php } ?>
                    </tr>
                    
                <?php $n++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php for($j=0;$j<sizeof($cart);$j++){?>
<div id="popup<?php echo $cart[$j]['cart_id'];?>" class="overlay">
	<div class="popup">
		<a class="close" href="#" id="hide<?php echo $cart[$j]['cart_id'];?>">&times;</a></br>
		<div class="content">
            Order Status:<br>
                <select class="uk-select" id="order_status<?php echo $cart[$j]['cart_id'];?>">
                  <option value="Pending" selected>Pending</option>
                    <option value="Confirmed">Confirmed</option>
                  <option value="Completed">Completed</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
                <br><br>
                <button type="button" onclick="update_status(<?php echo $cart[$j]['cart_id'];?>)">UPDATE</button>
		</div>
	</div>
</div>
<?php }?>
<script>
    function update_status(id)
    {
        var status = $('#order_status'+id).val();
        jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/update_order_status",
                    dataType: 'json',
                    data: {
                        id : id,
                       status :status
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                        }, false);
                        return xhr;
                    },
                    beforeSend: function () {
                        // $.blockUI({

                        //     message: '<h1>Please wait...</h1>'

                        // });
                    },
                    // complete: function () {
                    //     $.unblockUI();
                    // },
                    success: function (res) {
                        if (res.status == 'success') {
                            UIkit.notification({
                                message: 'Successfully updated',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 2000
                            });
                            
                         hide(id);
                            $("#status"+id).text(status);
                            window.location.href="https://intapps.fabricspa.com/jfsl/console/show_fabhome_cart_details/"+id;
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
      function hide(id)
    {
      $('#popup'+id).hide();
    }
    function update_fabhome_payment_status(cart_id)
    {
      jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/console_controller/update_fabhome_payment_status",
                    dataType: 'json',
                    data: {
                       cart_id : cart_id,
                    },
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        //Download progress
                        xhr.addEventListener("progress", function (evt) {
                        }, false);
                        return xhr;
                    },
                    beforeSend: function () {
                      
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            UIkit.notification({
                                message: 'Successfully updated',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 2000
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            UIkit.notification({
                                message: 'Updation failed',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }

                    }
                });
    }
</script>
