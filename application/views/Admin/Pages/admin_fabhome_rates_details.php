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
<a href="<?php echo site_url('console/fab_home');?>">Back</a><br><br>
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Rate Details</h3>
        <div class="uk-margin" >
        <div class="uk-form-controls">
            <select class="uk-select" id="search_data">
                <option value="Deep Cleaning" selected>Deep Cleaning</option>
                <option value="Office Cleaning">Office Cleaning</option>
                <option value="Home Cleaning">Home Cleaning</option>
            </select>
        </div>
    </div>
         <div class="uk-grid" id="deep_cleaning_rates">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Services</th>
                        <th scope="col">Category</th>
                        <th scope="col">UOM</th>
                        <th scope="col">Rate/UOM </th>
                        <th scope="col">Discount(%)</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Tax(%)</th>
                        <th scope="col">Expiry</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                       for ($i = 0; $i < sizeof($deepcleaning_rates); $i++) {?>
                    <tr>
                    <td scope="row"><?php echo $deepcleaning_rates[$i]['service'];?></td>
                        <td><?php echo $deepcleaning_rates[$i]['category']; ?></td>
                        <td><?php echo $deepcleaning_rates[$i]['input_uom']; ?></td>
                        <td><?php echo $deepcleaning_rates[$i]['rate_per_uom']; ?></td>
                        <td><?php echo $deepcleaning_rates[$i]['discount_percentage']; ?></td>
                        <?php if($deepcleaning_rates[$i]['discount_value'] == "0"){?>
                            <td></td>
                        <?php } else{?>
                            <td><?php echo $deepcleaning_rates[$i]['discount_value']; ?></td>
                        <?php } ?>
                        <td><?php echo $deepcleaning_rates[$i]['tax_percentage']; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($deepcleaning_rates[$i]['expiry'])); ?></td>
                        <?php if($deepcleaning_rates[$i]['active'] == 1){?>
                        <td><a href="<?php echo site_url('console/edit_rate/'.$deepcleaning_rates[$i]['Id']);?>">Edit</a></td>
                        <td><a href="<?php echo site_url('console/deactivate_rate/'.$deepcleaning_rates[$i]['Id']);?>" onclick="return confirm('Do you want to deactivate this rate?')">Deactivate</a></td>
                        <?php }else {?>
                            <td><a href="<?php echo site_url('console/activate_rate/'.$deepcleaning_rates[$i]['Id']);?>" onclick="return confirm('Do you want to activate this rate?')">Activate</a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!-- <p><?php echo $this->pagination->create_links(); ?></p> -->
        </div>
        <div class="uk-grid" id="office_cleaning_rates">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Services</th>
                        <th scope="col">Category</th>
                        <th scope="col">UOM</th>
                        <th scope="col">Rate/UOM </th>
                        <th scope="col">Discount(%)</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Tax(%)</th>
                        <th scope="col">Expiry</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                       for ($i = 0; $i < sizeof($officecleaning_rates); $i++) {?>
                    <tr>
                    <td scope="row"><?php echo $officecleaning_rates[$i]['service'];?></td>
                        <td><?php echo $officecleaning_rates[$i]['category']; ?></td>
                        <td><?php echo $officecleaning_rates[$i]['input_uom']; ?></td>
                        <td><?php echo $officecleaning_rates[$i]['rate_per_uom']; ?></td>
                        <td><?php echo $officecleaning_rates[$i]['discount_percentage']; ?></td>
                        <?php if($officecleaning_rates[$i]['discount_value'] == "0"){?>
                            <td></td>
                        <?php } else{?>
                            <td><?php echo $officecleaning_rates[$i]['discount_value']; ?></td>
                        <?php } ?>
                        <td><?php echo $officecleaning_rates[$i]['tax_percentage']; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($officecleaning_rates[$i]['expiry'])); ?></td>
                        <?php if($officecleaning_rates[$i]['active'] == 1){?>
                        <td><a href="<?php echo site_url('console/edit_rate/'.$officecleaning_rates[$i]['Id']);?>">Edit</a></td>
                        <td><a href="<?php echo site_url('console/deactivate_rate/'.$officecleaning_rates[$i]['Id']);?>" onclick="return confirm('Do you want to deactivate this rate?')">Deactivate</a></td>
                        <?php }else {?>
                            <td><a href="<?php echo site_url('console/activate_rate/'.$officecleaning_rates[$i]['Id']);?>" onclick="return confirm('Do you want to activate this rate?')">Activate</a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!-- <p><?php echo $this->pagination->create_links(); ?></p> -->
        </div>
        <div class="uk-grid" id="home_cleaning_rates">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Services</th>
                        <th scope="col">Category</th>
                        <th scope="col">UOM</th>
                        <th scope="col">Rate/UOM </th>
                        <th scope="col">Discount(%)</th>
                        <th scope="col">Discount</th>
                        <th scope="col">Tax(%)</th>
                        <th scope="col">Expiry</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                       for ($i = 0; $i < sizeof($homecleaning_rates); $i++) {?>
                    <tr>
                    <td scope="row"><?php echo $homecleaning_rates[$i]['service'];?></td>
                        <td><?php echo $homecleaning_rates[$i]['category']; ?></td>
                        <td><?php echo $homecleaning_rates[$i]['input_uom']; ?></td>
                        <td><?php echo $homecleaning_rates[$i]['rate_per_uom']; ?></td>
                        <td><?php echo $homecleaning_rates[$i]['discount_percentage']; ?></td>
                        <?php if($homecleaning_rates[$i]['discount_value'] == "0"){?>
                            <td></td>
                        <?php } else{?>
                            <td><?php echo $homecleaning_rates[$i]['discount_value']; ?></td>
                        <?php } ?>
                        <td><?php echo $homecleaning_rates[$i]['tax_percentage']; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($homecleaning_rates[$i]['expiry'])); ?></td>
                        <?php if($homecleaning_rates[$i]['active'] == 1){?>
                        <td><a href="<?php echo site_url('console/edit_rate/'.$homecleaning_rates[$i]['Id']);?>">Edit</a></td>
                        <td><a href="<?php echo site_url('console/deactivate_rate/'.$homecleaning_rates[$i]['Id']);?>" onclick="return confirm('Do you want to deactivate this rate?')">Deactivate</a></td>
                        <?php }else {?>
                            <td><a href="<?php echo site_url('console/activate_rate/'.$homecleaning_rates[$i]['Id']);?>" onclick="return confirm('Do you want to activate this rate?')">Activate</a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!-- <p><?php echo $this->pagination->create_links(); ?></p> -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#office_cleaning_rates').hide();
        $('#home_cleaning_rates').hide(); 
    });
    $("#search_data").change(function(){
        var service_type = $('#search_data').val();
        if(service_type == "Deep Cleaning"){
            $('#deep_cleaning_rates').show();
            $('#office_cleaning_rates').hide();
            $('#home_cleaning_rates').hide();  
        }else if(service_type == "Office Cleaning"){
            $('#deep_cleaning_rates').hide();
            $('#office_cleaning_rates').show();
            $('#home_cleaning_rates').hide();
        }else{
            $('#deep_cleaning_rates').hide();
            $('#office_cleaning_rates').hide();
            $('#home_cleaning_rates').show();
        }
    });
</script>
