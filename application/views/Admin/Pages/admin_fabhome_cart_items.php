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
/* button{
    background-color:white;
    color:black;
} */
</style>
<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
<a href="<?php echo site_url('console/fabhome_orders');?>">Back To Cart</a><br><br>
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Cart Details</h3>
         <div class="uk-grid" id="grid">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
			<th scope="col">Order Status</th>
                        <th scope="col">Appointment Address</th>
                        <th scope="col">Service</th>
                        <th scope="col">Category</th>
                        <th scope="col">Count</th>
                        <th scope="col">Price</th>
                        <th scope="col">Discount(%)</th>
                        <th scope="col">Discount(Rs)</th>
                        <th scope="col">Tax(%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n="1";  
                        for ($i = 0; $i < sizeof($cart_items); $i++)
                        {
                        ?>
                    <tr>
                        <td scope="row"><?php echo $n; ?></td>
			<td><?php echo $order_status;?></td>
                        <td><?php echo $address; ?></td>   
                        <td><?php if($cart_items[$i]['service'] != ""){ echo $cart_items[$i]['service'];}?></td>
                        <td><?php echo $cart_items[$i]['category']; ?></td>
                        <td><?php echo $cart_items[$i]['quantity']; ?></td>
                        <td><?php echo $cart_items[$i]['price']; ?></td>
                        <?php if($cart_items[$i]['discount_percentage'] != 0){?>
                            <td><?php echo $cart_items[$i]['discount_percentage']; ?></td>
                        <?php }else{?>
                            <td></td>
                        <?php } ?>
                        <?php if($cart_items[$i]['discount_value'] != 0){?>
                            <td><?php echo $cart_items[$i]['discount_value']; ?></td>
                        <?php }else{?>
                            <td></td>
                        <?php } ?>
                        <?php if($cart_items[$i]['tax_percentage'] != 0){?>
                            <td><?php echo $cart_items[$i]['tax_percentage']; ?></td>
                        <?php }else{?>
                            <td></td>
                        <?php } ?>  
                    </tr>
                <?php $n++; } ?>
                </tbody>
            </table>
            <!-- <p><?php echo $this->pagination->create_links(); ?></p> -->
        </div>
    </div>
</div>
