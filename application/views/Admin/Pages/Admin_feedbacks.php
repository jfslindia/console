<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 8/3/2018
 * Time: 9:08 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


?>


<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-padding">
        <div class="uk-card uk-card-body uk-card-default">
            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">Feedbacks</h3>

            <p class="uk-text-warning">Note: Showing only the latest 50 feedbacks</p>
            <ul class="uk-subnav uk-subnav-pill" uk-switcher>
                <li><a href="#">User Feedbacks</a></li>
                <li><a href="#">Order Feedbacks</a></li>

            </ul>

            <ul class="uk-switcher uk-margin">
                <li>
                    <div class="uk-overflow-auto" style="height: 800px;">
                    <table class="uk-table uk-table-striped uk-table-hover ">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Customer Code</th>
                            <th>Feedback</th>
                        </tr>
                        </thead>
                        <tbody class="f_body">
                        <?php for($i=0;$i<sizeof($feedbacks['user_feedbacks']);$i++){ ?>
                        <tr>
                            <td class="uk-width-1-6"><?php echo date("d-m-Y h:i:s A", strtotime($feedbacks['user_feedbacks'][$i]['date'])); ?></td>
                            <td class="uk-width-1-6"><?php echo $feedbacks['user_feedbacks'][$i]['name']; ?></td>
                            <td class="uk-width-1-6"><?php echo $feedbacks['user_feedbacks'][$i]['customer_id']; ?></td>
                            <td class="uk-width-expand"><?php echo $feedbacks['user_feedbacks'][$i]['feedback']; ?></td>
                        </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
                </li>
                <li>

                    <div class="uk-overflow-auto" style="height: 800px;">
                        <table class="uk-table uk-table-striped uk-table-hover">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer Name</th>
                                <th>Customer Code</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Order ID</th>
                                <th>Wash Rating</th>
                                <th>Reason For Low Wash Rating</th>
                                <th>Other Reason For Low Wash Rating</th>
                                <th>Overall Rating</th>
                                <th>Reason For Low Overall Rating</th>
                                <th>Other Reason For Low Overall Rating</th>
                                <th>Via</th>
                                <th>Source</th>




                            </tr>
                            </thead>
                            <tbody class="f_body">
                            <?php for($i=0;$i<sizeof($feedbacks['order_feedbacks']);$i++){ ?>
                                <tr>
                                    <td ><?php echo date("d-m-Y h:i:s A", strtotime($feedbacks['order_feedbacks'][$i]['Date'])); ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['CustomerName']; ?></td>
                                    <td><?php echo $feedbacks['order_feedbacks'][$i]['CustomerID']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['BranchCode']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['BranchName']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['OrderID']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['WashRating']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['ReasonForLowWashRating']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['OtherReasonForLowWashRating']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['OverallRating']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['ReasonForLowOverallRating']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['OtherReasonForLowOverallRating']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['Via']; ?></td>
                                    <td ><?php echo $feedbacks['order_feedbacks'][$i]['Source']; ?></td>

                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>

                </li>

            </ul>
        </div>
    </div>

<progress class="uk-progress" value="0" max="100" id="progressElem"></progress>

<style>
    .uk-subnav-pill>.uk-active>a {
        background-color: #18b6fc !important;
        color: #fff;
    }
    .f_body  tr  td {
        white-space: nowrap;
    }
    .uk-table td {
        padding: 8px 12px;
        vertical-align: top;
    }
</style>