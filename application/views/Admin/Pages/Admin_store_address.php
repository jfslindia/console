<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 11/28/2017
 * Time: 3:55 PM
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<script>

    window.onscroll=function(){changeMenu()}

    function changeMenu()
    {
        var scrollBarPosition = window.pageYOffset | document.body.scrollTop;

        if(scrollBarPosition >= 1000) {
            document.getElementById('to-top').style.display="block";
        }
        else {
            document.getElementById('to-top').style.display="none";
        }
    }

</script>
<div ng-controller="store">
    <div class="uk-container page-container">
        <div class="uk-card uk-card-body uk-card-default uk-margin-top">
            <table class="uk-table uk-table-hover uk-table-divider">
                <div class="uk-margin uk-text-right">
                    <button class="uk-button uk-button-default" ng-click="add_store()">Add a Store</button>
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
                       <a href="#" ng-click="orderByField='title'; reverseSort = !reverseSort">
                           Title <span ng-show="orderByField == 'title'"><span ng-show="!reverseSort"></span><span ng-show="reverseSort"></span></span>

                    </th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>
                        <a href="#" ng-click="orderByField='brand'; reverseSort = !reverseSort">
                            Brand <span ng-show="orderByField == 'brand'"><span ng-show="!reverseSort"></span><span ng-show="reverseSort"></span></span>

                    </th>
                    <th>
                        <a href="#" ng-click="orderByField='location'; reverseSort = !reverseSort">
                            Location <span ng-show="orderByField == 'location'"><span ng-show="!reverseSort"></span><span ng-show="reverseSort"></span></span>

                    </th>
                    
                    <th>Edit Store</th>
                    <th><button uk-icon="icon: trash; ratio:1.2;" ng-click="delete_stores()" title="Delete the store" uk-tooltip></button></th>
                </tr>
                </thead>
                <tbody>
                <!--<tr  ng-repeat="store in stores | filter:search">-->
               <tr  ng-repeat="store in results.stores |orderBy:orderByField:reverseSort | filter:search">
          <!--      <tr  ng-repeat="store in stores | filter:search">-->
                    <td><input type="checkbox" name="store_row" class="uk-checkbox" value="{{store.id}}"></td>
                    <td>{{serial_number = $index + 1}}</td>
                    <td>{{store.title}}</td>
                    <td style="max-width: 100px;">{{store.address}}</td>
                    <td>{{store.contactno}}</td>
                    <td>{{store.brand}}</td>
                    <td>{{store.location}}</td>

                    <td  >
                        <a href="#store_modal" ng-click="edit_store(store.id,results.stores.indexOf(store))" uk-icon="icon: pencil"></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="store_modal" uk-modal="bg-close:false">
        <div class="uk-modal-dialog">

            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title uk-inline" id="modal-title"></h2><span id="a_number"></span>
            </div>

            <div class="uk-modal-body" uk-overflow-auto>

                <form class="uk-form-horizontal uk-margin-large uk-form-width-large">
                    <input type="text" id="store_modal_details" hidden>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Title</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Name of the store" ng-model="form_title">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Address</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-textarea" placeholder="Address of the store" ng-model="form_address" rows="8"></textarea>

                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Email</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Email of the store" ng-model="form_email">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Contact Number</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Contact number of the store" ng-model="form_contactno">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Google Map link</label>
                        <div class="uk-form-controls">
                            <textarea class="uk-textarea" placeholder="GPS location of the store" ng-model="form_geo_location" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Brand</label>
                        <div class="uk-form-controls">

                            <select ng-model="form_brand" class="uk-select">
                                <option value="" ng-show="false">Select</option>
                                <option ng-repeat="brand in brands">{{brand.brand}}</option>
                            </select>

                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Location</label>
                        <div class="uk-form-controls">

                                <select ng-model="form_location" class="uk-select">
                                    <option value="" ng-show="false">Select</option>
                                    <option ng-repeat="location in locations">{{location.location}}</option>
                                </select>

                        </div>
                    </div>

                    <div class="uk-margin">
                        <label class="uk-form-label" for="form-horizontal-text">Price List Link</label>
                        <div class="uk-form-controls">

                            <input class="uk-input" id="form-horizontal-text" type="text" placeholder="Price list link to the JSON file" ng-model="form_price_list_link">

                        </div>
                    </div>

                </form>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary" type="button" ng-click="save_store()">Save</button>
            </div>

        </div>
    </div>

    <div id="spinner" class="loading-spiner-holder uk-flex uk-flex-middle" data-loading ><div class="loading-spiner"><img src="<?php echo base_url(); ?>layout/img/loading.gif" /></div></div>
    <a id="to-top" href="#" uk-totop="ratio: 2" uk-scroll="" class="uk-totop uk-icon" style="display: block;"><svg width="36" height="20" viewBox="0 0 18 10" xmlns="http://www.w3.org/2000/svg" ratio="2"><polyline fill="none" stroke="#000" stroke-width="1.2" points="1 9 9 1 17 9 "></polyline></svg></a>



