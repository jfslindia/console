<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/3/2018
 * Time: 9:08 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>
<script>

    window.onscroll = function () {
        changeMenu()
    }

    function changeMenu() {
        var scrollBarPosition = window.pageYOffset | document.body.scrollTop;

        if (scrollBarPosition >= 1000) {
            document.getElementById('to-top').style.display = "block";
        }
        else {
            document.getElementById('to-top').style.display = "none";
        }
    }

</script>


<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-padding">
    <div class="page-container">
        <div >

            <div class="uk-card uk-card-body uk-card-default">
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-hover uk-table-divider">
                        <div class="uk-margin uk-text-left">

                            <div class="uk-inline">
                                <a class="uk-form-icon" href="#" uk-icon="icon: search"></a>
                                <input class="uk-input " placeholder="Search Tag No" type="text" ng-model="search"
                                       ng-enter="search_tag_id()">

                            </div>

                            <button class="uk-button uk-button-default" onclick="reset_search()">Clear Search / Go back</button>

                        </div>

                        <thead>
                        <tr>
                            <!--<th>Select</th>
                            <th>#</th>-->
                            <th>
                                <a href="#" ng-click="orderByField='name'; reverseSort = !reverseSort">
                                    Date <span ng-show="orderByField == 'name'"><span
                                            ng-show="!reverseSort"></span><span ng-show="reverseSort"></span></span>

                            </th>
                            <th>Customer Code</th>
                            <th>Tag No</th>
                            <th>EGRN</th>

                            <th>Reasons</th>

                            <th>Status</th>
                            <th>More Details</th>


                        </tr>
                        </thead>
                        <tbody id="table_body">


                            <?php  for ($i = 0; $i < sizeof($logs); $i++) {

                                ?>
                            <tr id="table_row">
                                <td><?php echo $logs[$i]['CreatedDate']; ?></td>
                                <td><?php echo $logs[$i]['CustomerCode']; ?></td>
                                <td><?php echo $logs[$i]['TagNo']; ?></td>
                                <td><?php echo $logs[$i]['EGRNNo']; ?></td>

                              <!--  <?php /*for ($j = 0; $j < sizeof($reasons); $j++) { */?>
                                    <td><?php /*echo $logs[$i][$reasons[$j]['Reason']]; */?></td>

                                --><?php /*} */?>

                                <td><?php echo $logs[$i]['Reason'];?></td>


                                <td><?php echo $logs[$i]['Status']; ?></td>

                                <td><a href="<?php echo base_url(); ?>console/qc_log/<?php echo $logs[$i]['TagNo']; ?>">More Details</a></td>

                            </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>




            <div id="search_modal" uk-modal>
                <div class="uk-modal-dialog uk-modal-body" uk-overflow-auto>
                    <h2 class="uk-modal-title uk-text-center">Search result</h2>

                    <div id="search_result_block">

                    </div>
                    <p class="uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Close</button>
                    </p>
                </div>
            </div>

        </div>


    </div>
</div>

<!--</div>-->


<script>
    $('.multipleSelect').fastselect();
</script>

<style>
    .fstElement {
        font-size: 12px;
    }

    .fstMultipleMode .fstControls {
        width: 100%;
    }

    #qa_user_modal > div > div.uk-modal-body.uk-overflow-auto > form > div:nth-child(8) > div > div > div:nth-child(3) > div {
        width: -webkit-fill-available;
    }

    .uk-table th, .uk-table td {
        text-align: center !important;
    }
</style>
<!--<div id="spinner" style="z-index: 999999" class="loading-spiner-holder uk-flex uk-flex-middle" data-loading>
    <div class="loading-spiner"><img src="<?php /*echo base_url(); */ ?>assets/images/loading.gif"/></div>
</div>-->
<a id="to-top" href="#" uk-totop="ratio: 2" uk-scroll="" class="uk-totop uk-icon" style="display: block;">
    <svg width="36" height="20" viewBox="0 0 18 10" xmlns="http://www.w3.org/2000/svg" ratio="2">
        <polyline fill="none" stroke="#000" stroke-width="1.2" points="1 9 9 1 17 9 "></polyline>
    </svg>
</a>


<script>
    $("#filter_text").on("keyup", function () {
        var value = $(this).val().toLowerCase();

        $("#stores_block > label").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


</script>


<?php if(!$logs) { ?>

    <script>
        UIkit.notification({
            message: 'No result to show!',
            status: 'warning',
            pos: 'bottom-center',
            timeout: 4000

        });
        setTimeout(function(){ window.location = base_url + "console/qa_logs"; }, 4000);
    </script>
<?php } ?>

<script>
    function reset_search(){
        window.location = base_url + "console/qc_logs";
    }
</script>