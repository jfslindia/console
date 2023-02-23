<!-- * Created by PhpStorm.
 * User: targetman
 * Date: 4/5/2017
 * Time: 1:43 PM
 */-->


<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

?>


<script>


    $(document).ready(function () {

        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_total_users",
            dataType: 'json',
            data: {},
            success: function (res) {

                $('#chartgroup_2_warning').addClass('uk-hidden');
              var users_chart = new Chart($('#total_users_chart'), {
                 type: 'pie',
                 data: {
                 labels: ["Fabricspa", "Click2Wash"],
                 datasets: [{
                 label: "Total registered Users",
                 backgroundColor: ["#1e87f0", "#15d0ff"],
                 data: [res['result']['Fabricspa'], res['result']['Click2Wash']]
                 }]
                 },
                 options: {

                 title: {
                 display: true,
                 text: 'Total customer statistics'
                 }
                 }
                 });

                var users_genderwise_chart = new Chart($('#total_users_genderwise_chart'), {
                    type: 'pie',
                    data: {
                        labels: ["Male", "Female"],
                        datasets: [{
                            label: "Total registered Users based on gender",
                            backgroundColor: ["#1e87f0", "#15d0ff"],
                            data: [res['result']['Males'], res['result']['Females']]
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        title: {
                            display: true,
                            text: 'Customer gender statistics (Only valid ones)'
                        }
                    }
                });

               /*  $('#fab-graph-label').html('Users registered via Fabricspa: <span class="uk-badge">' + res['result']['Fabricspa'] + '</span>');
                 $('#cw-graph-label').html('Users registered via Click2Wash: <span class="uk-badge">' + res['result']['Click2Wash'] + '</span>');
                 $('#total-graph-label').html('Total Users Count: <span class="uk-badge">' + ((res['result']['Fabricspa']) + (res['result']['Click2Wash'])) + '</span>');*/

                 $('#male_count').html('Male users count: <span class="uk-badge">' + res['result']['Males'] + '</span>');
                 $('#female_count').html('Female users count: <span class="uk-badge">' + res['result']['Females'] + '</span>');


                 var table_body = '';
                 for (var i = 0; i < res['result']['age_group'].length; i++) {
                 table_body = table_body + '<tr>' +
                 '<td>' + res['result']['age_group'][i]['age'] + '</td>' +
                 '<td>' + res['result']['age_group'][i]['count'] + '</td>' +
                 '</tr>'
                 }

                 $('#age_group_table_body').html('');
                 $('#age_group_table_body').html(table_body);


                 var table_body = '';
                 for (var i = 0; i < res['result']['areawise_users'].length; i++) {
                 table_body = table_body + '<tr>' +
                 '<td>' + res['result']['areawise_users'][i]['area'] + '</td>' +
                 '<td class="city">' + res['result']['areawise_users'][i]['location'] + '</td>' +
                 '<td>' + res['result']['areawise_users'][i]['count'] + '</td>' +
                 '</tr>'
                 }

                 $('#arewise_users_table_body').html('');
                 $('#arewise_users_table_body').html(table_body);

            }

        });
    });



    $(document).ready(function () {



        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_fab_source",
            dataType: 'json',
            data: {},
            success: function (res) {

                var fab_source_chart = new Chart($('#fabricspa_source'), {
                    type: 'pie',
                    data: {
                        labels: ["Website", "Android", "iOS"],
                        datasets: [{
                            label: "Fabricspa user registration source",
                            backgroundColor: ["#1e87f0", "#18b6fc", "#fc9618"],
                            data: [res['result']['web_count'], res['result']['android_count'], res['result']['ios_count']]
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Fabricspa Registration Source'
                        }
                    }
                });
            }, error: function (res) {
                //error
            }
        });

        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_cw_source",
            dataType: 'json',
            data: {},
            success: function (res) {


                var cw_source_chart = new Chart($('#click2wash_source'), {
                    type: 'pie',
                    data: {
                        labels: ["Website", "Android", "iOS"],
                        datasets: [{
                            label: "Click2Wash user registration source",
                            backgroundColor: ["#1e87f0", "#18b6fc", "#12b6fc"],
                            data: [res['result']['web_count'], res['result']['android_count'], res['result']['ios_count']]
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Click2Wash Registration Source'
                        }
                    }
                });
            }, error: function (res) {
                // error
            }

        });
    });



    $(document).ready(function () {


            /*var chart_width = $('.chart-wrapper').width() / 2;

             $('.chart').css('width', chart_width);*/


            /*AJAX CALL for bar charts*/


            jQuery.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_registers",
                dataType: 'json',
                data: {},
                success: function (res) {
                    $('#chart_1_warning').addClass('uk-hidden');
                    var size = res.result.length;
                    var label = [];
                    var data = [];
                    for (var i = 0; i < size; i++) {

                        label.push(res.result[i]['date']);
                        data.push(res.result[i]['reg']);
                    }


                    var myChart1 = new Chart($("#last_week_status_chart"), {
                        type: 'bar',
                        data: {
                            labels: label,
                            datasets: [
                                {
                                    label: "User Registrations",
                                    backgroundColor: [
                                        "#1e87f0", "#1e8df3", "#249df3", "#1bb1f9", "#19b0f9", "#1cabfc", "#1caefb", "#15d0ff","#36d3fb","#49d2f5","#49d2f5","#88e5fd","#9be4f7","#b0e7f5","#c9f4ff"
                                    ],
                                    borderColor: [
                                        "#1e87f0", "#1e8df3", "#249df3", "#1bb1f9", "#19b0f9", "#1cabfc", "#1caefb", "#15d0ff","#36d3fb","#49d2f5","#49d2f5","#88e5fd","#9be4f7","#b0e7f5","#c9f4ff"
                                    ],
                                    borderWidth: 1,
                                    data: data
                                }
                            ]
                        },
                        options: {
                            responsive:true,
                            maintainAspectRatio: false,
                            scales: {
                                xAxes: [{
                                    stacked: true
                                }],

                                yAxes: [{
                                    display: true,
                                    ticks: {
                                        beginAtZero: true,
                                        /*steps: 10,*/
                                        stepValue: 1,
                                        max: 140

                                    }
                                }]
                            },
                            title: {
                                display: true,
                                text: 'Registrations'
                            }
                        }
                    });


                },
                error: function (res) {

                    //console.log(res);
                }
            });


        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_orders",
            dataType: 'json',
            data: {},
            success: function (res) {
                $('#chart_2_warning').addClass('uk-hidden');
                var size = res.result.length;
                var label = [];
                var data = [];
                for (var i = 0; i < size; i++) {

                    label.push(res.result[i]['date']);
                    data.push(res.result[i]['orders']);
                }

                var myChart2 = new Chart($("#order_schedules_chart"), {
                    type: 'bar',
                    data: {
                        labels: label,
                        datasets: [
                            {
                                label: "User Pickups",
                                backgroundColor: [
                                    "#1e87f0", "#1e8df3", "#249df3", "#1bb1f9", "#19b0f9", "#1cabfc", "#1caefb", "#15d0ff","#36d3fb","#49d2f5","#49d2f5","#88e5fd","#9be4f7","#b0e7f5","#c9f4ff"
                                ],
                                borderColor: [
                                    "#1e87f0", "#1e8df3", "#249df3", "#1bb1f9", "#19b0f9", "#1cabfc", "#1caefb", "#15d0ff","#36d3fb","#49d2f5","#49d2f5","#88e5fd","#9be4f7","#b0e7f5","#c9f4ff"
                                ],
                                borderWidth: 1,
                                data: data
                            }
                        ]
                    },
                    options: {
                        responsive:true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                stacked: true
                            }],

                            yAxes: [{
                                display: true,
                                ticks: {
                                    beginAtZero: true,
                                    /*steps: 10,*/
                                    stepValue: 1,
                                    max: 140

                                }
                            }]
                        },
                        title: {
                            display: true,
                            text: 'Pickups'
                        }
                    }
                });


            },
            error: function (res) {

                //console.log(res);
            }
        });



/*
    jQuery.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_fab_source",
        dataType: 'json',
        data: {},
        success: function (res) {

            var fab_source_chart = new Chart($('#fabricspa_source'), {
                type: 'pie',
                data: {
                    labels: ["Website", "Android", "iOS"],
                    datasets: [{
                        label: "Fabricspa user registration source",
                        backgroundColor: ["#1e87f0", "#18b6fc", "#12b6fc"],
                        data: [res['result']['web_count'], res['result']['mobile_count'], res['result']['ios_count'], res['result']['google_count'], res['result']['fb_count']]
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Fabricspa Registration Source'
                    }
                }
            });
        }, error: function (res) {
            //error
        }
    });


    jQuery.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>" + "index.php/console_controller/dashboard_cw_source",
        dataType: 'json',
        data: {},
        success: function (res) {


            var cw_source_chart = new Chart($('#click2wash_source'), {
                type: 'pie',
                data: {
                    labels: ["Website", "Android", "iOS"],
                    datasets: [{
                        label: "Click2Wash user registration source",
                        backgroundColor: ["#1e87f0", "#18b6fc", "#12b6fc"],
                        data: [res['result']['web_count'], res['result']['mobile_count'], res['result']['ios_count'], res['result']['google_count'], res['result']['fb_count']]
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Click2Wash Registration Source'
                    }
                }
            });
        }, error: function (res) {
            // error
        }

    });*/


    })
    ;

</script>


<div class="uk-width-1-1@s uk-width-4-5@m uk-margin-auto-left content_wrapper uk-margin-top uk-padding uk-padding-remove-left">

    <div id="page-1-container">

        <div
            class="uk-grid uk-grid-divider uk-grid-medium uk-child-width-1-2 uk-child-width-1-4@l uk-child-width-1-5@xl"
            data-uk-grid>
            <div>
                <span class="uk-text-small"><span data-uk-icon="icon:users"
                                                  class="uk-margin-small-right uk-text-primary"></span>New Users</span>

                <h1 class="uk-heading-primary uk-margin-remove  uk-text-primary">53</h1>

                <div class="uk-text-small">
                    <span class="uk-text-success" data-uk-icon="icon: triangle-up">15%</span> more than last week.
                </div>
            </div>
            <div>

                <span class="uk-text-small"><span data-uk-icon="icon:cart"
                                                  class="uk-margin-small-right uk-text-primary"></span>Pickups</span>

                <h1 class="uk-heading-primary uk-margin-remove uk-text-primary">31</h1>

                <div class="uk-text-small">
                    <span class="uk-text-warning" data-uk-icon="icon: triangle-down">-15%</span> less than last week.
                </div>

            </div>
            <div>

                <span class="uk-text-small"><span data-uk-icon="icon:clock"
                                                  class="uk-margin-small-right uk-text-primary"></span>Traffic hours</span>

                <h1 class="uk-heading-primary uk-margin-remove uk-text-primary">12.00
                    <small class="uk-text-small">PM</small>
                </h1>
                <div class="uk-text-small">
                    <span class="uk-text-success" data-uk-icon="icon: triangle-up"> 19%</span> more than last week.
                </div>

            </div>
            <div>

                <span class="uk-text-small"><span data-uk-icon="icon:search"
                                                  class="uk-margin-small-right uk-text-primary"></span>Week Search</span>

                <h1 class="uk-heading-primary uk-margin-remove uk-text-primary">9.543</h1>

                <div class="uk-text-small">
                    <span class="uk-text-danger" data-uk-icon="icon: triangle-down"> -23%</span> less than last week.
                </div>

            </div>
            <div class="uk-visible@xl">
                <span class="uk-text-small"><span data-uk-icon="icon:users"
                                                  class="uk-margin-small-right uk-text-primary"></span>Lorem ipsum</span>

                <h1 class="uk-heading-primary uk-margin-remove uk-text-primary">5.284</h1>

                <div class="uk-text-small">
                    <span class="uk-text-success" data-uk-icon="icon: triangle-up"> 7%</span> more than last week.
                </div>
            </div>
        </div>


        <div class="uk-grid uk-grid-medium uk-grid-match" data-uk-grid>

            <!-- panel -->
            <div class="uk-width-1-2@l">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Registrations Chart</h4></div>
                            <div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">
                            <canvas id="last_week_status_chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /panel -->
            <!-- panel -->
            <div class="uk-width-1-2@l">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Pickups Chart</h4></div>
                            <div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">
                            <canvas id="order_schedules_chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /panel -->
            <!-- panel -->
            <div class="uk-width-1-1 uk-width-1-2@l uk-width-1-2@xl">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Area Chart</h4></div>
                            <div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">
                            <!--<canvas id="chart3"></canvas>-->

                            <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-striped" >
                                <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>City</th>
                                    <th>Users</th>
                                </tr>
                                </thead>
                                <tbody id="arewise_users_table_body">

                                </tbody>
                            </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /panel -->

            <!-- panel -->
            <div class="uk-width-1-1 uk-width-1-2@m uk-width-1-4@l">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Age Chart</h4></div>
                            <div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">
                            <!--<canvas id="chart5"></canvas>-->
                            <table class="uk-table uk-table-striped">
                                <thead>
                                <tr>
                                    <th>Age</th>
                                    <th>Total Users</th>
                                </tr>
                                </thead>
                                <tbody id="age_group_table_body">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /panel -->

            <!-- panel -->
            <div class="uk-width-1-1 uk-width-1-2@m uk-width-1-4@l">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Gender Chart</h4></div>
                            <!--<div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>-->
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">

                            <canvas id="total_users_genderwise_chart" height="200" width="200"  class="table" style="margin:0 auto;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="uk-card uk-card-default uk-card-small uk-card-hover uk-margin-top">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Users Chart</h4></div>
                            <!--<div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>-->
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">

                            <canvas id="total_users_chart" height="200" width="200"  class="table" style="margin:0 auto;"></canvas>
                            <p id="fab-graph-label"></p>
                            <p id="cw-graph-label"></p>
                            <p id="total-graph-label"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /panel -->


            <!-- panel -->
            <div class="uk-width-1-1 uk-width-1-4@l">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Fabricspa Chart</h4></div>
                            <div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">


                            <canvas id="fabricspa_source" height="200" width="200"  class="table" ></canvas>

                        </div>
                    </div>
                </div>
            </div>

            <!-- /panel -->


            <!-- panel -->
            <div class="uk-width-1-1 uk-width-1-4@l">
                <div class="uk-card uk-card-default uk-card-small uk-card-hover">
                    <div class="uk-card-header">
                        <div class="uk-grid uk-grid-small">
                            <div class="uk-width-auto"><h4>Click2Wash Chart</h4></div>
                            <div class="uk-width-expand uk-text-right panel-icons">
                                <a href="#" class="uk-icon-link" title="Move" data-uk-tooltip data-uk-icon="icon: move"></a>
                                <a href="#" class="uk-icon-link" title="Configuration" data-uk-tooltip data-uk-icon="icon: cog"></a>
                                <a href="#" class="uk-icon-link" title="Close" data-uk-tooltip data-uk-icon="icon: close"></a>
                            </div>
                        </div>
                    </div>
                    <div class="uk-card-body">
                        <div class="chart-container">


                            <canvas id="click2wash_source" height="200" width="200"  class="table"></canvas>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


<style type="text/css">
    #age_group_table_body > tr > td, .city {
        text-align: center;
    }
</style>