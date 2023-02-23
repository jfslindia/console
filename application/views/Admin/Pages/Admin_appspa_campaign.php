<?php
/**
 * Created by PhpStorm.
 * User: targetman
 * Date: 3/16/2019
 * Time: 9:00 AM
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="uk-width-1-1@s uk-width-4-5@m uk-width-5-6@m uk-margin-auto-left content_wrapper uk-margin-top uk-padding">
        <div class="uk-card uk-card-body uk-card-default">
            <!--uk-width-1-2@l uk-width-1-2@m uk-width-1-2@s-->
            <h3 class="uk-heading-divider uk-text-center">APPSPA Campaign</h3>

            <p class="uk-text-center uk-text-warning">Below stats are generated on <?php echo Date('d-m-Y h:i:s A'); ?></p>

            <H5 class="uk-text-center uk-heading-divider">Last 24 hrs statistics</H5>
            <table class="uk-table uk-table-hover uk-table-divider">
                <thead>
                <tr>
                    <th>Stat</th>
                    <th>Count</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Registrations in the last 24 hrs who got the coupon code APPSPA</td>
                    <td><?php echo $stats['last_24hrs_registrations_with_coupon']['TotalRegistrationsInLast24HrsWhoGotTheCoupon']; ?></td>

                </tr>

                <tr>
                    <td>Registrations in the last 24 hrs who didn't get the coupon code APPSPA</td>
                    <td><?php echo $stats['last_24hrs_registrations_without_coupon']['TotalRegistrationsInLast24HrsWithoutCoupon']; ?></td>

                </tr>

                <tr>
                    <td>Registrations in the last 24 hrs</td>
                    <td><?php echo $stats['last_24hrs_registrations']['TotalRegistrationsInLast24Hrs']; ?></td>

                </tr>

                <tr>
                    <td>Total pickups with APPSPA coupon from users who registered in the last 24 hrs</td>
                    <td><?php echo $stats['last_24hrs_pickups_with_coupon']['TotalPickupsByNewlyRegisteredUsersWhoAppliedCoupon']; ?></td>

                </tr>
                <tr>
                    <td>Total pickups without APPSPA coupon from users who registered in the last 24 hrs</td>
                    <td><?php echo $stats['last_24hrs_pickups_without_coupon']['TotalPickupsByNewlyRegisteredUsersWithoutCoupon']; ?></td>

                </tr>

                <tr>
                    <td>Total pickups in the last 24 hrs from users who registered in the last 24 hrs</td>
                    <td><?php echo $stats['last_24hrs_pickups']['TotalPickupsByNewlyRegisteredUsers']; ?></td>
                </tr>

                </tbody>
            </table>

            <hr class="uk-divider-icon">

            <H5 class="uk-text-center uk-heading-divider">Statistics since start of the campaign (March 9, 2019)</H5>
            <table class="uk-table uk-table-hover uk-table-divider">
                <thead>
                <tr>
                    <th>Stat</th>
                    <th>Count</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Registrations with the coupon code APPSPA</td>
                    <td><?php echo $stats['total_registrations_with_coupon']['TotalRegistrationsSinceCampaignWhoGotCoupon']; ?></td>

                </tr>

                <tr>
                    <td>Registrations without the coupon code APPSPA</td>
                    <td><?php echo $stats['total_registrations_without_coupon']['TotalRegistrationsSinceCampaignWithoutCoupon']; ?></td>

                </tr>

                <tr>
                    <td>Registrations since start of the campaign</td>
                    <td><?php echo $stats['total_registrations']['TotalRegistrationsSinceCampaign']; ?></td>

                </tr>

                <tr>
                    <td>Total pickups with APPSPA</td>
                    <td><?php echo $stats['total_pickups_with_appspa']['TotalPickupsSinceCampaignWhoAppliedCoupon']; ?></td>

                </tr>
                <tr>
                    <td>Total pickups without APPSPA</td>
                    <td><?php echo $stats['total_pickups_without_appspa']['TotalPickupsSinceCampaignWithoutCoupon']; ?></td>

                </tr>

                <tr>
                    <td>Total pickups since start of the campaign</td>
                    <td><?php echo $stats['total_pickups']['TotalPickupsSinceCampaign']; ?></td>
                </tr>

                </tbody>
            </table>

            </div>
    </div>