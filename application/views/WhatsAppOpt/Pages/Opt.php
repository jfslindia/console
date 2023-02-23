<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div ng-app="whatsapp_opt" block-ui="main" class="block-ui-main">
    <div class="uk-margin-large-top" ng-controller="opt">
        <div class="uk-container uk-container-medium">
            <h3>Hello customer, check below the box to opt in/out from WhatsApp messages from Fabricspa.</h3>

            <div>
                <form>
                    <input type="hidden" id="field" customer_id=<?php echo $details['CustomerCode']; ?>>

                    <input class="uk-checkbox" type="checkbox" checked ng-model="opt" ng-true-value="1"
                           ng-false-value="0" ng-init="opt=<?php echo $details['Opt']; ?>" ng-change="change()">

                    <span ng-show="opt=='1'">I want to receive messages on WhatsApp from Fabricspa.</span>

                    <span ng-show="opt=='0'">I <b>do not</b> want to receive messages on WhatsApp from Fabricspa.</span>

                    <p >

                        <button class="uk-button uk-button-default fabspa-confirm" ng-click="opt_in_out()">Submit</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .block-ui-message{
        display: none;
    }
</style>