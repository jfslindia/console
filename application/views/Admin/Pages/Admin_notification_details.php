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
<a href="<?php echo site_url('Console_Controller/admin_send_notifications');?>">Back To Send Page</a><br><br>
    <div class="uk-card uk-align-center uk-card-default uk-card-hover uk-card-body">
        <h3 class="uk-heading-divider uk-text-center">Notification Details</h3>
         <div class="uk-grid" id="grid">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Notification</th>
                        <th scope="col">Total Recievers</th>
                        <th scope="col">Status</th>
                        <th scope="col">Schedule Date</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n="1";  
                        for ($i = 0; $i < sizeof($data); $i++)
                        {
                            if($data[$i]['status'] == "Scheduled")
                            {
                                $cancel = 'Cancel';                          
                            } else{
                                $cancel = "";
                            }
                        ?>
                    <tr>
                        <td scope="row"><?php echo $n; ?></td>
                        <td><?php echo $data[$i]['title']; ?></td>
                        <td><?php echo $data[$i]['total_receivers']; ?></td>
                        <td><?php echo $data[$i]['status']; ?></td>
                        <?php if($data[$i]['schedule_date'] != ""){
                            $data[$i]['schedule_date'] = date("d-m-Y", strtotime($data[$i]['schedule_date']));
                        }?>
                        <td><?php echo $data[$i]['schedule_date']; ?></td>
                        <td><button type="button"><a href="<?php echo site_url('Console_Controller/show_users_details/'.$data[$i]['notification_id']);?>">View More</a></button></td>  
                        <td><a href="<?php echo site_url('console/cancel_scheduled_notifications/'.$data[$i]['notification_id']);?>" onclick="return confirm('Are you sure do you want to cancel?')"><?php  echo $cancel;?></a></td>
                    </tr>
                <?php $n++; } ?>
                </tbody>
            </table>
            <!-- <p><?php echo $this->pagination->create_links(); ?></p> -->
        </div>
    </div>
</div>
