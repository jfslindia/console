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

        <div ng-controller="qa_finished_by_users">
            <div class="uk-card uk-card-body uk-card-default">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <div class="uk-margin uk-text-right">
                        <button class="uk-button uk-button-default" ng-click="add_qa_finished_by_user()">Add a finished by user</button>
                        <div class="uk-inline">
                            <a class="uk-form-icon" href="#" uk-icon="icon: search"></a>
                            <input class="uk-input " placeholder="Search here..." type="text" ng-model="search">
                        </div>
                    </div>

                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>#</th>
                        <th>
                            <a href="#" ng-click="orderByField='name'; reverseSort = !reverseSort">
                                Name <span ng-show="orderByField == 'name'"><span
                                        ng-show="!reverseSort"></span><span ng-show="reverseSort"></span></span>

                        </th>



                        <th>Edit</th>
                        <th>
                            <button uk-icon="icon: trash; ratio:1.2;" ng-click="delete_qa_finished_by_users()"
                                    title="Delete the finished by user" uk-tooltip></button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <!--<tr  ng-repeat="qa_finished_by_user in qa_finished_by_users | filter:search">-->
                    <tr ng-repeat="qa_finished_by_user in results.qa_finished_by_users |orderBy:orderByField:reverseSort | filter:search">
                        <!--      <tr  ng-repeat="qa_finished_by_user in qa_finished_by_users | filter:search">-->
                        <td><input type="checkbox" name="qa_finished_by_user_row" class="uk-checkbox" value="{{qa_finished_by_user.Id}}">
                        </td>
                        <td>{{serial_number = $index + 1}}</td>
                        <td>{{qa_finished_by_user.Name}}</td>



                        <td>
                            <a href="#qa_finished_by_user_modal"
                               ng-click="edit_qa_finished_by_user(qa_finished_by_user.Id,results.qa_finished_by_users.indexOf(qa_finished_by_user))"
                               uk-icon="icon: pencil"></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>


            <div id="qa_finished_by_user_modal" class="uk-modal-container" uk-modal="bg-close:false">
                <div class="uk-modal-dialog">

                    <button class="uk-modal-close-default" type="button" uk-close></button>
                    <div class="uk-modal-header">
                        <h2 class="uk-modal-title uk-inline" id="modal-title"></h2><span id="a_number"></span>
                    </div>

                    <div class="uk-modal-body">

                        <form class="uk-form-horizontal">
                            <input type="text" id="qa_finished_by_user_modal_details" hidden>

                            <div class="uk-margin">
                                <label class="uk-form-label" for="form-horizontal-text">Name</label>

                                <div class="uk-form-controls">
                                    <input class="uk-input" id="form-horizontal-text" type="text"
                                           placeholder="Name of the user" ng-model="form_name" ng-enter="save_qa_finished_by_user()">
                                </div>
                            </div>


                        </form>
                    </div>

                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                        <button class="uk-button uk-button-primary" type="button" ng-click="save_qa_finished_by_user()">Save
                        </button>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>

</div>


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

    #qa_finished_by_user_modal > div > div.uk-modal-body.uk-overflow-auto > form > div:nth-child(8) > div > div > div:nth-child(3) > div {
        width: -webkit-fill-available;
    }

    .uk-table th {
        text-align: left !important;
    }
</style>
<!--<div id="spinner" style="z-index: 999999" class="loading-spiner-holder uk-flex uk-flex-middle" data-loading>
    <div class="loading-spiner"><img src="<?php /*echo base_url(); */?>assets/images/loading.gif"/></div>
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


