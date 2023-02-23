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
</style>
<div id="page-1-container" class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
<a href="<?php echo site_url('Console_Controller/get_notification_details');?>">Back To Notification Page</a><br>
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">User Details</h3>
         <div class="uk-grid" id="grid">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">MobileNumber</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        for ($i = 0; $i < sizeof($data); $i++)
                        {
                    ?>
                    <tr>
                        <?php if($data[$i]['mobile_number'] != ""){ ?>
                            <td><?php echo $data[$i]['mobile_number']; ?></td>
                            <td><?php echo $data[$i]['status']; ?></td>
                        <?php } ?> 
                    </tr>
                    <?php  }?>
                </tbody>
            </table>    
         </div>
    </div>
</div>    