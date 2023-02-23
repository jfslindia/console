var jfsl_admin = angular.module('jfsl_admin', ['chieffancypants.loadingBar', 'blockUI', 'ngMessages']);


jfsl_admin.config(function (cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
});

jfsl_admin.config(function (blockUIConfig) {

    // Change the default overlay message
    //blockUIConfig.message = 'Please wait...';
    blockUIConfig.message = '';


    // Change the default delay to 100ms before the blocking is visible
    blockUIConfig.delay = 100;

});

jfsl_admin.directive('ngNumber', function () {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function (scope, element, attrs, ctrl) {
            ctrl.$parsers.push(function (input) {
                if (input == undefined) return ''
                var inputNumber = input.toString().replace(/[^0-9]/g, '');
                if (inputNumber != input) {
                    ctrl.$setViewValue(inputNumber);
                    ctrl.$render();
                }
                return inputNumber;
            });
        }
    };
});

/*jfsl_admin.directive('loading', ['$http', function ($http) {
 return {
 restrict: 'A',
 link: function (scope, elm, attrs) {
 scope.isLoading = function () {
 return $http.pendingRequests.length > 0;
 };

 scope.$watch(scope.isLoading, function (v) {
 if (v) {
 elm.show();
 } else {
 elm.hide();
 }
 });
 }
 };

 }]);*/

jfsl_admin.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13) {
                scope.$apply(function () {
                    scope.$eval(attrs.ngEnter, {'event': event});
                });

                event.preventDefault();
            }
        });
    };
});

/*
 CONTROLLER TEMPLATE
 jfsl_admin.controller('search_panel', function ($scope, $http, $compile,cfpLoadingBar,blockUI) {

 $scope.start = function () {
 cfpLoadingBar.start();
 };

 $scope.complete = function () {
 cfpLoadingBar.complete();
 };

 $scope.func=function(){

 blockUI.start();
 $http({
 method: "POST",
 url: base_url+"admin_controller/change_password",
 data: {


 }
 }).then(function success(response) {

 if (response.data.status=='success') {

 blockUI.stop();

 }else{

 }

 }
 });
 });*/


jfsl_admin.controller('search_panel', function ($scope, $http, $compile, cfpLoadingBar, blockUI, $compile) {

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    };

    $scope.search = function (search_form) {

        var search_with = $scope.search_with;
        var search_text = $scope.search_text;
        $scope.users = [];

        /*if(search_with!=undefined) {*/

        if (search_form.search_with.$valid && search_form.search_text.$valid) {

            blockUI.start();
            $http({
                method: "POST",
                url: base_url + "Admin_Controller/search_panel",
                data: {

                    search_with: search_with,
                    search_text: search_text
                }
            }).then(function success(response) {

                if (response.data.status == 'success') {

                    blockUI.stop();

                    $scope.users = response.data.result.user_details;


                    $('#user_table_body').html('');
                    $('#orders_table_body').html('');


                    UIkit.notification({
                        message: 'Succesfully retreived results',
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    if (search_with != 'name') {

                        for (var i = 0; i < response.data.result.user_details.length; i++) {


                            $scope.customer_name = response.data.result.user_details[i]['name'];
                            $scope.customer_email = response.data.result.user_details[i]['email'];
                            $scope.customer_mobile = response.data.result.user_details[i]['mobile_number'];

                            var user_details_result = '<tr>' +

                                '<td>' + response.data.result.user_details[i]['customer_id'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['date'] + ' </td>' +
                                '<td>{{customer_name}} </td>' +
                                '<td>{{customer_mobile}} </td>' +
                                '<td>{{customer_email}} </td>' +
                                '<td> <button class="uk-button uk-button-default" ng-click="edit_user(' + i + ')"><span uk-icon="icon: pencil"></span> Edit</button> </td>' +

                                '</tr>';

                            var html = $compile(user_details_result)($scope);
                            angular.element(document.getElementById("user_table_body")).append(html);
                        }


                        $('#user_table_block').show();

                        if (response.data.result.order_details.length > 0) {


                            for (var i = 0; i < response.data.result.order_details.length; i++) {

                                $('#orders_table_body').append('<tr>' +

                                    '<td>' + response.data.result.order_details[i]['date'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i]['pick_up_date'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i]['customer_id'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i]['bookingID'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i]['reference_number'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i]['order_status'] + ' </td>' +
                                    '</tr>'
                                )
                            }

                            $('#orders_table_block').show();
                        } else {
                            $('#orders_table_body').html('')
                            $('#orders_table_block').hide();
                        }

                    } else {

                        for (var i = 0; i < response.data.result.user_details.length; i++) {

                            /* $('#user_table_body').append('<tr>' +

                             '<td>' + response.data.result.user_details[i]['customer_id'] + ' </td>' +
                             '<td>' + response.data.result.user_details[i]['date'] + ' </td>' +
                             '<td>' + response.data.result.user_details[i]['name'] + ' </td>' +
                             '<td>' + response.data.result.user_details[i]['mobile_number'] + ' </td>' +
                             '<td>' + response.data.result.user_details[i]['email'] + ' </td>' +
                             '</tr>'
                             )*/

                            user_details_result = '<tr>' +

                            '<td>' + response.data.result.user_details[i]['customer_id'] + ' </td>' +
                            '<td>' + response.data.result.user_details[i]['date'] + ' </td>' +
                            '<td>' + response.data.result.user_details[i]['name'] + '</td>' +
                            '<td>' + response.data.result.user_details[i]['mobile_number'] + '</td>' +
                            '<td>' + response.data.result.user_details[i]['email'] + ' </td>' +
                            '<td> <button class="uk-button uk-button-default" ng-click="edit_user(' + i + ')"><span uk-icon="icon: check"></span> Edit</button> </td>' +

                            '</tr>';

                            html = $compile(user_details_result)($scope);
                            angular.element(document.getElementById("user_table_body")).append(html);


                            $('#user_table_block').show();

                            for (var j = 0; j < response.data.result.order_details[i].length; j++) {


                                $('#orders_table_body').append('<tr>' +

                                    '<td>' + response.data.result.order_details[i][j]['date'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i][j]['pick_up_date'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i][j]['customer_id'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i][j]['bookingID'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i][j]['reference_number'] + ' </td>' +
                                    '<td>' + response.data.result.order_details[i][j]['order_status'] + ' </td>' +
                                    '</tr>'
                                )
                            }
                            $('#orders_table_block').show();

                        }
                    }

                } else if (response.data.status == 'failed') {

                    blockUI.stop();

                    UIkit.notification({
                        message: 'Nothing to show',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    $('#user_table_body').html('');
                    $('#orders_table_body').html('');


                    $('#user_table_block').hide();
                    $('#orders_table_block').hide();
                }

                else if (response.data.status == "error") {

                    blockUI.stop();

                    UIkit.notification({
                        message: response.data.message,
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    $('#user_table_body').html('');
                    $('#orders_table_body').html('');


                    $('#user_table_block').hide();
                    $('#orders_table_block').hide();

                } else {

                    blockUI.stop();

                    UIkit.notification({
                        message: 'Error',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    $('#user_table_body').html('');
                    $('#orders_table_body').html('');


                    $('#user_table_block').hide();
                    $('#orders_table_block').hide();
                }
            });
        } else {
            UIkit.notification({
                message: 'Form validation error',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
        }

    }


    $scope.edit_user = function (index) {

        var user = $scope.users[index];


        $scope.customer_name = user['name'];
        $scope.customer_mobile = parseInt(user['mobile_number']);
        $scope.customer_email = user['email'];


        angular.element(document.getElementById("customer_name")).val(user['name']);
        angular.element(document.getElementById("customer_mobile")).val(parseInt(user['mobile_number']));
        angular.element(document.getElementById("customer_email")).val(user['email']);

        var save_user_button = '<button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button><button class="uk-button uk-button-default uk-modal-close" ng-click="save_user(' + user["id"] + ',' + index + ')" >Save</button>'


        var button = $compile(save_user_button)($scope);

        $compile("#customer_name")($scope);
        $compile("#customer_mobile")($scope);
        $compile("#customer_email")($scope);


        angular.element(document.getElementById("edit_user_footer")).html('');
        angular.element(document.getElementById("edit_user_footer")).append(button);

        UIkit.modal($('#user_edit')).show();

    }

    $scope.save_user = function (id, index) {


        var user_id = id;
        var name = $scope.edit_user_form.customer_name.$modelValue;
        var phone = $scope.edit_user_form.customer_mobile.$modelValue;
        var email = $scope.edit_user_form.customer_email.$modelValue;


        if ($scope.edit_user_form.customer_name.$valid && $scope.edit_user_form.customer_mobile.$valid && $scope.edit_user_form.customer_email.$valid) {

            blockUI.start();

            $http({
                method: "POST",
                url: base_url + "admin_controller/save_user",
                data: {
                    user_id: user_id,
                    name: name,
                    phone: phone,
                    email: email

                }
            }).then(function success(response) {

                blockUI.stop();

                if (response.data.status == 'success') {


                    UIkit.notification({
                        message: response.data.message,
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                    $scope.users[index] = response.data.user_details;

                    $scope.customer_name = response.data.user_details.name;
                    $scope.customer_mobile = response.data.user_details.mobile_number;
                    $scope.customer_email = response.data.user_details.email;

                } else if (response.data.status == 'failed') {

                    UIkit.notification({
                        message: response.data.message,
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                } else {

                    UIkit.notification({
                        message: 'Failed to update',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 1000
                    });
                }

            });
        }
    }


});

jfsl_admin.controller('change_passwd', function ($scope, $http, $compile, cfpLoadingBar, blockUI) {

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    };

    $scope.change_password = function () {

        var old_password = $scope.old_password;

        var new_password = $scope.new_password;

        var re_enter_password = $scope.re_enter_password;

        //event.preventDefault();


        if (old_password != new_password) {

            if (new_password == re_enter_password) {

                if (new_password != '') {

                    blockUI.start();

                    $http({
                        method: "POST",
                        url: base_url + "admin_controller/change_password",
                        data: {
                            old_password: old_password,
                            new_password: new_password

                        }
                    }).then(function success(response) {


                        if (response.data.status == 'success') {
                            //console.log('ok');

                            UIkit.notification({
                                message: 'Password changed',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 1000
                            });

                            setTimeout(function () {

                                window.location = base_url + "console/logout";
                            }, 1000);

                        }
                        else if (response.data.status == 'failed') {

                            blockUI.stop();

                            UIkit.notification({
                                message: 'Failed',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }

                        else if (response.data.status == 'wrong_password') {

                            blockUI.stop();

                            UIkit.notification({
                                message: 'Wrong Password',
                                status: 'warning',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    });

                } else {
                    blockUI.stop();
                    UIkit.notification({
                        message: 'Password can not be empty',
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }
            }

            else {

                blockUI.stop();

                UIkit.notification({
                    message: 'Enter the same password',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
            }


        } else {

            blockUI.stop();

            UIkit.notification({
                message: 'Enter a new password',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 1000
            });

        }
    }
});


jfsl_admin.controller('qc_logs', function ($scope, $http, $compile, cfpLoadingBar, blockUI) {
    $scope.mode = 'default';

    /*$scope.get_qc_logs=get_qc_logs();
     $scope.get_qc_reasons=get_qc_reasons();*/

    $scope.results = [];

    //$scope.get_qc_data_for_chart = get_qc_data_for_chart();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;


    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }

  /*  $scope.qc_logs_close_chart = function () {

        $('#chart_card').hide();
    }*/


    $scope.generate_report = function () {

        var proceed=false;
        var start_datetime=null;
        var end_datetime=null;


        var time_period=$scope.time_period;

        console.log(time_period)

        if(time_period == false){

            start_datetime=null;
            end_datetime=null;
            proceed=true;
        }else{

            if($scope.start_date && $scope.end_date){

                proceed=true;

                if(!$scope.start_time){
                    $scope.start_time='00';
                }
                if(!$scope.end_time){
                    $scope.end_time='00';
                }

                start_datetime = $scope.start_date + ' ' + $scope.start_time + ':00:00'/*+'.000'*/;
                end_datetime = $scope.end_date + ' ' + $scope.end_time + ':00:00'/*+'.000'*/;
            }else{
                proceed=false;
            }

        }

        if(proceed) {

            blockUI.start();
            $http({
                method: "POST",
                url: base_url + "admin_controller/generate_qc_logs_report",
                data: {
                    start_datetime: start_datetime,
                    end_datetime: end_datetime
                }

            }).then(function success(response) {
                blockUI.stop();
                if (response.data.status == 'success') {

                    UIkit.notification({
                        message: response.data.message,
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 60000

                    });
                } else {
                    UIkit.notification({
                        message: response.data.message,
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 4000

                    });
                }
            });
        }else{
            UIkit.notification({
                message: 'Please enter valid details',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 4000

            });
        }


    }

    /*Searching the logs based on tag id*/
    $scope.search_tag_id = function () {
        blockUI.start();
        if ($scope.search != '' && $scope.search != undefined) {

            window.location = base_url + "console/qc_logs?keyword=" + $scope.search
        }
        else {
            blockUI.stop();
            UIkit.notification({
                message: 'Please enter a valid search query!',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 4000

            });
        }


    }


    function get_qc_logs() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qc_logs"

        }).then(function success(response) {
            blockUI.stop();


            if (response.data.logs.length > 0) {

                $scope.results = {
                    qc_logs: response.data.logs
                };

            } else {

                $scope.results = {
                    qc_logs: []
                };
            }


        }, function error(response) {
            //console.log(response)
        });
    }

    function get_qc_reasons() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qc_reasons"

        }).then(function success(response) {
            blockUI.stop();


            if (response.data.length > 0) {

                $scope.results = {
                    qc_reasons: response.data
                };

            } else {

                $scope.results = {
                    qc_reasons: []
                };
            }


        }, function error(response) {
            //console.log(response)
        });
    }

    function get_qc_data_for_chart() {
        blockUI.start();
        var reasons = [];
        var count = [];
        var colors = ['red', 'yellow', 'blue', 'lavender', 'green', 'orange', 'violet', 'brown']
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qc_data_for_chart"

        }).then(function success(response) {
            blockUI.stop();

            if (response.data.length > 0) {

                for (var i = 0; i < response.data.length; i++) {

                    reasons.push(response.data[i].reason);
                    count.push(response.data[i].count);
                }

                var pie_chart = new Chart($('#qc_logs_pie_chart'), {
                    type: 'pie',
                    data: {
                        labels: reasons,
                        datasets: [{
                            label: "Total registered Users",
                            backgroundColor: colors,
                            data: count
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Total qc logs statistics'
                        }
                    }
                });


            } else {

            }

        }, function error(response) {
            //console.log(response)
        });
    }

});


jfsl_admin.controller('qa_logs', function ($scope, $http, $compile, cfpLoadingBar, blockUI) {
    $scope.mode = 'default';

    /*$scope.get_qa_logs=get_qa_logs();
     $scope.get_qa_reasons=get_qa_reasons();*/

    $scope.results = [];

    $scope.get_qa_data_for_chart = get_qa_data_for_chart();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;


    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }

    $scope.qa_logs_close_chart = function () {

        $('#chart_card').hide();
    }


    $scope.generate_report = function () {

        var proceed=false;
        var start_datetime=null;
        var end_datetime=null;


        var time_period=$scope.time_period;

        console.log(time_period)

        if(time_period == false){

            start_datetime=null;
            end_datetime=null;
            proceed=true;
        }else{

            if($scope.start_date && $scope.end_date){

                proceed=true;

                if(!$scope.start_time){
                    $scope.start_time='00';
                }
                if(!$scope.end_time){
                    $scope.end_time='00';
                }

                start_datetime = $scope.start_date + ' ' + $scope.start_time + ':00:00'/*+'.000'*/;
                end_datetime = $scope.end_date + ' ' + $scope.end_time + ':00:00'/*+'.000'*/;
            }else{
                proceed=false;
            }

        }

        if(proceed) {

            blockUI.start();
            $http({
                method: "POST",
                url: base_url + "admin_controller/generate_qa_logs_report",
                data: {
                    start_datetime: start_datetime,
                    end_datetime: end_datetime
                }

            }).then(function success(response) {
                blockUI.stop();
                if (response.data.status == 'success') {

                    UIkit.notification({
                        message: response.data.message,
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 60000

                    });
                } else {
                    UIkit.notification({
                        message: response.data.message,
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 4000

                    });
                }
            });
        }else{
            UIkit.notification({
                message: 'Please enter valid details',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 4000

            });
        }


    }

    /*Searching the logs based on tag id*/
    $scope.search_tag_id = function () {
        blockUI.start();
        if ($scope.search != '' && $scope.search != undefined) {

            window.location = base_url + "console/qa_logs?keyword=" + $scope.search
        }
        else {
            blockUI.stop();
            UIkit.notification({
                message: 'Please enter a valid search query!',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 4000

            });
        }


    }


    function get_qa_logs() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qa_logs"

        }).then(function success(response) {
            blockUI.stop();


            if (response.data.logs.length > 0) {

                $scope.results = {
                    qa_logs: response.data.logs
                };

            } else {

                $scope.results = {
                    qa_logs: []
                };
            }


        }, function error(response) {
            //console.log(response)
        });
    }

    function get_qa_reasons() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qa_reasons"

        }).then(function success(response) {
            blockUI.stop();


            if (response.data.length > 0) {

                $scope.results = {
                    qa_reasons: response.data
                };

            } else {

                $scope.results = {
                    qa_reasons: []
                };
            }


        }, function error(response) {
            //console.log(response)
        });
    }

    function get_qa_data_for_chart() {
        blockUI.start();
        var reasons = [];
        var count = [];
        var colors = ['red', 'yellow', 'blue', 'lavender', 'green', 'orange', 'violet', 'brown']
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qa_data_for_chart"

        }).then(function success(response) {
            blockUI.stop();

            if (response.data.length > 0) {

                for (var i = 0; i < response.data.length; i++) {

                    reasons.push(response.data[i].reason);
                    count.push(response.data[i].count);
                }

                var pie_chart = new Chart($('#qa_logs_pie_chart'), {
                    type: 'pie',
                    data: {
                        labels: reasons,
                        datasets: [{
                            label: "Total registered Users",
                            backgroundColor: colors,
                            data: count
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Total QA logs statistics'
                        }
                    }
                });


            } else {

            }

        }, function error(response) {
            //console.log(response)
        });
    }

});


jfsl_admin.controller('qa_reasons', function ($scope, $http, cfpLoadingBar, blockUI) {


    $scope.mode = 'default';

    $scope.results = [];
    $scope.get_qa_reasons = get_qa_reasons();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;


    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }

    $scope.add_qa_reason = function () {
        $scope.mode = 'add';
        $('#qa_reason_modal_details').removeAttr('index');
        $('#qa_reason_modal_details').removeAttr('qa_reason_id');

        $('#modal-title').html('Add a QA Reason');

        $scope.form_reason = '';

        UIkit.modal("#qa_reason_modal").show();

    }

    $scope.save_qa_reason = function () {

        var qa_reason = $scope.form_reason;


        if (qa_reason) {

            UIkit.modal("#qa_reason_modal").hide();

            $http({
                method: "POST",
                url: base_url + "Admin_Controller/save_qa_reason",
                data: {
                    mode: $scope.mode,
                    id: $('#qa_reason_modal_details').attr('qa_reason_id'),
                    qa_reason: qa_reason


                }
            }).then(function success(response) {

                if (response.data.length == 1 && !response.data.status) {

                    UIkit.modal("#qa_reason_modal").hide();

                    var qa_reason = response.data[0];

                    if ($scope.mode == 'add') {

                        $scope.results.qa_reasons.push(qa_reason);

                    } else if ($scope.mode == 'edit') {

                        var index = $('#qa_reason_modal_details').attr('index');
                        $scope.results.qa_reasons[index] = qa_reason;
                    }

                    UIkit.notification({
                        message: 'Saved!',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 4000

                    });

                } else {
                    if (response.data.status == 'existing') {
                        UIkit.notification({
                            message: response.data.message,
                            status: 'warning',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    } else {
                        UIkit.notification({
                            message: 'Saving failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    }

                }

            }, function error(response) {
                console.log(response);
            });

        } else {
            UIkit.notification({
                message: 'Please fill up reason field!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });
        }


    }

    function get_qa_reasons() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qa_reasons"

        }).then(function success(response) {
            blockUI.stop();

            if (response.data.length > 0) {
                $scope.results = {
                    qa_reasons: response.data
                };
            } else {
                $scope.results = {
                    qa_reasons: []
                };

                UIkit.notification({
                    message: 'No reasons found',
                    status: 'warning',
                    pos: 'bottom-center',
                    timeout: 1200

                });
            }

        }, function error(response) {
            //console.log(response)
        });
    }

    $scope.edit_qa_reason = function (id, index) {

        $('#qa_reason_modal_details').attr('index', index);
        $('#qa_reason_modal_details').attr('qa_reason_id', id);


        $scope.mode = 'edit';
        $('#modal-title').html('Edit : ' + $scope.results.qa_reasons[index].Reason);

        $scope.form_reason = $scope.results.qa_reasons[index].Reason;


        UIkit.modal("#qa_reason_modal").show();
    }

    $scope.delete_qa_reasons = function () {

        var selected_qa_reasons = [];

        $("input[name='qa_reason_row']:checked").each(function () {
            selected_qa_reasons.push($(this).val());
        });

        if (selected_qa_reasons.length && selected_qa_reasons) {

            UIkit.modal.confirm('Do you want to delete this reason?').then(function () {
                $http({
                    method: "post",
                    data: {
                        "selected_qa_reasons": selected_qa_reasons
                    },
                    url: base_url + "Admin_Controller/delete_qa_reasons"
                }).then(function success(response) {
                    if (response.data.status == 'success') {
                        UIkit.notification({
                            message: 'Succesfully deleted',
                            status: 'success',
                            pos: 'top-right',
                            timeout: 1200

                        });
                        setTimeout(function () {

                            location.reload();
                        }, 1000);
                    } else {
                        UIkit.notification({
                            message: 'Deletion failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 1200

                        });
                    }

                }, function error(response) {

                });

            }, function () {
                //else
            });

        } else {
            UIkit.notification({
                message: 'Please select a qa_reason!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });


        }

    }

});


jfsl_admin.controller('qa_users', function ($scope, $http, $compile, cfpLoadingBar, blockUI) {
    $scope.mode = 'default';

    $scope.results = [];
    $scope.get_qa_users = get_qa_users();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;


    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }

    $scope.add_qa_user = function () {
        $scope.mode = 'add';
        $('#qa_user_modal_details').removeAttr('index');
        $('#qa_user_modal_details').removeAttr('qa_user_id');

        $('#modal-title').html('Add a QA user');

        $scope.form_name = '';
        $scope.form_contactno = '';
        $scope.form_privilege = '';
        $scope.form_passwd = '';


        UIkit.modal("#qa_user_modal").show();

    }

    $scope.save_qa_user = function () {

        var qa_user_name = $scope.form_name;
        var qa_user_contactno = $scope.form_contactno;

        var qa_user_passwd = $scope.form_passwd;


        if (qa_user_name && qa_user_contactno && qa_user_passwd) {

            UIkit.modal("#qa_user_modal").hide();

            $http({
                method: "POST",
                url: base_url + "Admin_Controller/save_qa_user",
                data: {
                    mode: $scope.mode,
                    id: $('#qa_user_modal_details').attr('qa_user_id'),
                    name: qa_user_name,
                    contactno: qa_user_contactno,
                    passwd: qa_user_passwd


                }
            }).then(function success(response) {

                if (response.data.length == 1 && !response.data.status) {

                    UIkit.modal("#qa_user_modal").hide();

                    var qa_user = response.data[0];

                    if ($scope.mode == 'add') {

                        $scope.results.qa_users.push(qa_user);

                    } else if ($scope.mode == 'edit') {

                        var index = $('#qa_user_modal_details').attr('index');
                        $scope.results.qa_users[index] = qa_user;
                    }

                    UIkit.notification({
                        message: 'Saved!',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 4000

                    });

                } else {
                    if (response.data.status == 'existing') {
                        UIkit.notification({
                            message: response.data.message,
                            status: 'warning',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    } else {
                        UIkit.notification({
                            message: 'Saving failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    }

                }

            }, function error(response) {
                console.log(response);
            });

        } else {
            UIkit.notification({
                message: 'Please fill up Name/Phone/Password details!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });
        }


    }

    function get_qa_users() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qa_users"

        }).then(function success(response) {
            blockUI.stop();

            if (response.data.length > 0) {
                $scope.results = {
                    qa_users: response.data
                };
            } else {
                $scope.results = {
                    qa_users: []
                };
            }

        }, function error(response) {
            //console.log(response)
        });
    }

    $scope.edit_qa_user = function (id, index) {

        $('#qa_user_modal_details').attr('index', index);
        $('#qa_user_modal_details').attr('qa_user_id', id);


        $scope.mode = 'edit';
        $('#modal-title').html('Edit : ' + $scope.results.qa_users[index].Name);

        $scope.form_name = $scope.results.qa_users[index].Name;
        $scope.form_contactno = $scope.results.qa_users[index].Phone;

        $scope.form_passwd = $scope.results.qa_users[index].Password;


        UIkit.modal("#qa_user_modal").show();
    }

    $scope.delete_qa_users = function () {

        var selected_qa_users = [];

        $("input[name='qa_user_row']:checked").each(function () {
            selected_qa_users.push($(this).val());
        });

        if (selected_qa_users.length && selected_qa_users) {

            UIkit.modal.confirm('Do you want to delete this qa_user?').then(function () {
                $http({
                    method: "post",
                    data: {
                        "selected_qa_users": selected_qa_users
                    },
                    url: base_url + "Admin_Controller/delete_qa_users"
                }).then(function success(response) {
                    if (response.data.status == 'success') {
                        UIkit.notification({
                            message: 'Succesfully deleted',
                            status: 'success',
                            pos: 'top-right',
                            timeout: 1200

                        });
                        setTimeout(function () {

                            location.reload();
                        }, 1000);
                    } else {
                        UIkit.notification({
                            message: 'Deletion failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 1200

                        });
                    }

                }, function error(response) {

                });

            }, function () {
                //else
            });

        } else {
            UIkit.notification({
                message: 'Please select a qa_user!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });


        }

    }

});


jfsl_admin.controller('qa_finished_by_users', function ($scope, $http, $compile, cfpLoadingBar, blockUI) {
    $scope.mode = 'default';

    $scope.results = [];
    $scope.get_qa_finished_by_users = get_qa_finished_by_users();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;


    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }

    $scope.add_qa_finished_by_user = function () {
        $scope.mode = 'add';
        $('#qa_finished_by_user_modal_details').removeAttr('index');
        $('#qa_finished_by_user_modal_details').removeAttr('qa_finished_by_user_id');

        $('#modal-title').html('Add a QA finished by user');

        $scope.form_name = '';


        UIkit.modal("#qa_finished_by_user_modal").show();

    }

    $scope.save_qa_finished_by_user = function () {

        var qa_finished_by_user_name = $scope.form_name;


        if (qa_finished_by_user_name) {

            UIkit.modal("#qa_finished_by_user_modal").hide();

            $http({
                method: "POST",
                url: base_url + "Admin_Controller/save_qa_finished_by_user",
                data: {
                    mode: $scope.mode,
                    id: $('#qa_finished_by_user_modal_details').attr('qa_finished_by_user_id'),
                    name: qa_finished_by_user_name


                }
            }).then(function success(response) {

                if (response.data.length == 1 && !response.data.status) {

                    UIkit.modal("#qa_finished_by_user_modal").hide();

                    var qa_finished_by_user = response.data[0];

                    if ($scope.mode == 'add') {

                        $scope.results.qa_finished_by_users.push(qa_finished_by_user);

                    } else if ($scope.mode == 'edit') {

                        var index = $('#qa_finished_by_user_modal_details').attr('index');
                        $scope.results.qa_finished_by_users[index] = qa_finished_by_user;
                    }

                    UIkit.notification({
                        message: 'Saved!',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 4000

                    });

                } else {
                    if (response.data.status == 'existing') {
                        UIkit.notification({
                            message: response.data.message,
                            status: 'warning',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    } else {
                        UIkit.notification({
                            message: 'Saving failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    }

                }

            }, function error(response) {
                console.log(response);
            });

        } else {
            UIkit.notification({
                message: 'Please fill up Name!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });
        }


    }

    function get_qa_finished_by_users() {
        blockUI.start();
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qa_finished_by_users"

        }).then(function success(response) {
            blockUI.stop();

            if (response.data.length > 0) {
                $scope.results = {
                    qa_finished_by_users: response.data
                };
            } else {
                $scope.results = {
                    qa_finished_by_users: []
                };
            }

        }, function error(response) {
            //console.log(response)
        });
    }

    $scope.edit_qa_finished_by_user = function (id, index) {

        $('#qa_finished_by_user_modal_details').attr('index', index);
        $('#qa_finished_by_user_modal_details').attr('qa_finished_by_user_id', id);


        $scope.mode = 'edit';
        $('#modal-title').html('Edit : ' + $scope.results.qa_finished_by_users[index].Name);

        $scope.form_name = $scope.results.qa_finished_by_users[index].Name;


        UIkit.modal("#qa_finished_by_user_modal").show();
    }

    $scope.delete_qa_finished_by_users = function () {

        var selected_qa_finished_by_users = [];

        $("input[name='qa_finished_by_user_row']:checked").each(function () {
            selected_qa_finished_by_users.push($(this).val());
        });

        if (selected_qa_finished_by_users.length && selected_qa_finished_by_users) {

            UIkit.modal.confirm('Do you want to delete this Finished By user?').then(function () {
                $http({
                    method: "post",
                    data: {
                        "selected_qa_finished_by_users": selected_qa_finished_by_users
                    },
                    url: base_url + "Admin_Controller/delete_qa_finished_by_users"
                }).then(function success(response) {
                    if (response.data.status == 'success') {
                        UIkit.notification({
                            message: 'Succesfully deleted',
                            status: 'success',
                            pos: 'top-right',
                            timeout: 1200

                        });
                        setTimeout(function () {

                            location.reload();
                        }, 1000);
                    } else {
                        UIkit.notification({
                            message: 'Deletion failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 1200

                        });
                    }

                }, function error(response) {

                });

            }, function () {
                //else
            });

        } else {
            UIkit.notification({
                message: 'Please select a qa_finished_by_user!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });


        }

    }

});

jfsl_admin.controller('dcr', function ($scope, $http, $compile, cfpLoadingBar) {
    $scope.mode = 'default';

    $scope.results = [];
    $scope.get_dcr_users = get_dcr_users();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }


    function get_dcr_users() {
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_dcr_users"

        }).then(function success(response) {

            if (response.data.length > 0) {
                $scope.results = {
                    dcr_users: response.data
                };
            } else {
                $scope.results = {
                    dcr_users: []
                };
            }

        }, function error(response) {
            //console.log(response)
        });
    }


    $scope.edit_dcr_user = function (id, index) {

        $('#dcr_user_modal_details').attr('index', index);
        $('#dcr_user_modal_details').attr('dcr_user_id', id);


        $scope.mode = 'edit';
        $('#modal-title').html('Edit : ' + $scope.results.dcr_users[index].Name);

        $scope.form_name = $scope.results.dcr_users[index].Name;
        $scope.form_contactno = $scope.results.dcr_users[index].Phone;


        $scope.form_privilege = $scope.results.dcr_users[index].Privilege;

        var stores = JSON.parse($scope.results.dcr_users[index].Branches);


        $("input[name='selected_stores']").prop("checked", false);

        for (i = 0; i < stores.length; i++) {

            $("input[name='selected_stores'][value=" + stores[i] + "]").prop("checked", true);
        }


        $scope.form_passwd = $scope.results.dcr_users[index].Password;


        UIkit.modal("#dcr_user_modal").show();
    }


    $scope.add_dcr_user = function () {
        $scope.mode = 'add';
        $('#dcr_user_modal_details').removeAttr('index');
        $('#dcr_user_modal_details').removeAttr('dcr_user_id');

        $('#modal-title').html('Add a DCR user');

        $scope.form_name = '';
        $scope.form_contactno = '';
        $scope.form_privilege = '';
        $scope.form_passwd = '';

        $("input[name='selected_stores']").prop('checked', false);

        UIkit.modal("#dcr_user_modal").show();

    }

    $scope.save_dcr_user = function () {

        var dcr_user_name = $scope.form_name;
        var dcr_user_contactno = $scope.form_contactno;
        var dcr_user_privilege = $scope.form_privilege;

        var dcr_user_passwd = $scope.form_passwd;

        var dcr_user_branches = [];
        var dcr_user_branch_names = [];
        $("input[name='selected_stores']:checked").each(function () {
            dcr_user_branches.push($(this).val());
            dcr_user_branch_names.push($(this).parent().text());
        });


        if (dcr_user_name && dcr_user_contactno && dcr_user_passwd) {

            UIkit.modal("#dcr_user_modal").hide();

            $http({
                method: "POST",
                url: base_url + "Admin_Controller/save_dcr_user",
                data: {
                    mode: $scope.mode,
                    id: $('#dcr_user_modal_details').attr('dcr_user_id'),
                    name: dcr_user_name,
                    contactno: dcr_user_contactno,
                    privilege: dcr_user_privilege,

                    passwd: dcr_user_passwd,
                    "stores[]": dcr_user_branches,
                    "store_names[]": dcr_user_branch_names

                }
            }).then(function success(response) {

                if (response.data.length == 1 && !response.data.status) {

                    UIkit.modal("#dcr_user_modal").hide();

                    var dcr_user = response.data[0];

                    if ($scope.mode == 'add') {

                        $scope.results.dcr_users.push(dcr_user);

                    } else if ($scope.mode == 'edit') {

                        var index = $('#dcr_user_modal_details').attr('index');
                        $scope.results.dcr_users[index] = dcr_user;
                    }

                    UIkit.notification({
                        message: 'Saved!',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 4000

                    });

                } else {
                    if (response.data.status == 'existing') {
                        UIkit.notification({
                            message: response.data.message,
                            status: 'warning',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    } else {
                        UIkit.notification({
                            message: 'Saving failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    }

                }

            }, function error(response) {
                console.log(response);
            });

        } else {
            UIkit.notification({
                message: 'Please fill up Name/Phone/Password and associated branch details!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });
        }


    }

    $scope.delete_dcr_users = function () {

        var selected_dcr_users = [];

        $("input[name='dcr_user_row']:checked").each(function () {
            selected_dcr_users.push($(this).val());
        });

        if (selected_dcr_users.length && selected_dcr_users) {

            UIkit.modal.confirm('Do you want to delete this dcr_user?').then(function () {
                $http({
                    method: "post",
                    data: {
                        "selected_dcr_users": selected_dcr_users
                    },
                    url: base_url + "Admin_Controller/delete_dcr_users"
                }).then(function success(response) {
                    if (response.data.status == 'success') {
                        UIkit.notification({
                            message: 'Succesfully deleted',
                            status: 'success',
                            pos: 'top-right',
                            timeout: 1200

                        });
                        setTimeout(function () {

                            location.reload();
                        }, 1000);
                    } else {
                        UIkit.notification({
                            message: 'Deletion failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 1200

                        });
                    }

                }, function error(response) {

                });

            }, function () {
                //else
            });

        } else {
            UIkit.notification({
                message: 'Please select a dcr_user!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });


        }

    }
});

jfsl_admin.controller('store', function ($scope, $http) {

    $scope.mode = 'default';

    $scope.get_stores = get_stores();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;

    $scope.locations = [
        {"location": "Bangalore"}, {"location": "Mumbai"},
        {"location": "Chennai"}, {"location": "Delhi"}, {"location": "Pune"}, {"location": "Mangalore"}, {"location": "Hubli-Dharwad"}, {"location": "Shimoga"}, {"location": "Mysore"}, {"location": "Hosur"}
    ];

    $scope.brands = [

        {'brand': 'Fabricspa'},
        {'brand': 'Click2Wash'}

    ];

    function get_stores() {
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_stores"

        }).then(function success(response) {

            if (response.data.length > 0) {
                $scope.results = {
                    stores: response.data
                };
            }

        }, function error(response) {
            //console.log(response)
        });
    }

    $scope.edit_store = function (id, index) {

        $('#store_modal_details').attr('index', index);
        $('#store_modal_details').attr('store_id', id);


        $scope.mode = 'edit';
        $('#modal-title').html('Edit : ' + $scope.results.stores[index].title);

        $scope.form_title = $scope.results.stores[index].title;
        $scope.form_address = $scope.results.stores[index].address;
        $scope.form_email = $scope.results.stores[index].email;
        $scope.form_contactno = $scope.results.stores[index].contactno;
        $scope.form_geo_location = $scope.results.stores[index].map;
        $scope.form_brand = $scope.results.stores[index].brand;
        $scope.form_location = $scope.results.stores[index].location;
        $scope.form_price_list_link = $scope.results.stores[index].price_list_link;

        UIkit.modal("#store_modal").show();
    }

    $scope.add_store = function () {
        $scope.mode = 'add';
        $('#store_modal_details').removeAttr('index');
        $('#store_modal_details').removeAttr('store_id');

        $('#modal-title').html('Add a store');

        $scope.form_title = '';
        $scope.form_address = '';
        $scope.form_email = '';
        $scope.form_contactno = '';
        $scope.form_geo_location = '';
        $scope.form_brand = undefined;
        $scope.form_location = undefined;
        UIkit.modal("#store_modal").show();

    }

    $scope.save_store = function () {

        var store_title = $scope.form_title;
        var store_address = $scope.form_address;
        var store_email = $scope.form_email;
        var store_contactno = $scope.form_contactno;
        var store_map = $scope.form_geo_location;
        var store_brand = $scope.form_brand;
        var store_location = $scope.form_location;
        var store_price_list_link = $scope.form_price_list_link;

        if (store_title != undefined && store_address != undefined && store_brand != undefined && store_location != undefined) {

            UIkit.modal("#store_modal").hide();

            $http({
                method: "POST",
                url: base_url + "Admin_Controller/save_store",
                data: {
                    mode: $scope.mode,
                    id: $('#store_modal_details').attr('store_id'),
                    title: store_title,
                    address: store_address,
                    email: store_email,
                    contactno: store_contactno,
                    map: store_map,
                    brand: store_brand,
                    location: store_location,
                    link: store_price_list_link
                }
            }).then(function success(response) {

                if (response.data.length == 1 && !response.data.status) {

                    UIkit.modal("#store_modal").hide();

                    var store = response.data[0];

                    if ($scope.mode == 'add') {

                        $scope.results.stores.push(store);

                    } else if ($scope.mode == 'edit') {
                        console.log(store);
                        var index = $('#store_modal_details').attr('index');
                        $scope.results.stores[index] = store;
                    }

                    UIkit.notification({
                        message: 'Store saved!',
                        status: 'success',
                        pos: 'top-right',
                        timeout: 4000

                    });

                } else {
                    if (response.data.status == 'existing') {
                        UIkit.notification({
                            message: response.data.message,
                            status: 'warning',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    } else {
                        UIkit.notification({
                            message: 'Saving failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 4000

                        });
                    }

                }

            }, function error(response) {
                console.log(response);
            });

        } else {
            UIkit.notification({
                message: 'Please fill up Title /  Address / Brand / Location fields!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });
        }


    }

    $scope.delete_stores = function () {

        var selected_stores = [];

        $("input[name='store_row']:checked").each(function () {
            selected_stores.push($(this).val());
        });

        if (selected_stores.length && selected_stores) {

            UIkit.modal.confirm('Do you want to delete this store?').then(function () {
                $http({
                    method: "post",
                    data: {
                        "selected_stores": selected_stores
                    },
                    url: base_url + "Admin_Controller/delete_stores"
                }).then(function success(response) {
                    if (response.data.status == 'success') {
                        UIkit.notification({
                            message: 'Store(s) succesfully deleted',
                            status: 'success',
                            pos: 'top-right',
                            timeout: 1200

                        });
                        setTimeout(function () {

                            location.reload();
                        }, 1000);
                    } else {
                        UIkit.notification({
                            message: 'Store(s) deletion failed!',
                            status: 'danger',
                            pos: 'top-right',
                            timeout: 1200

                        });
                    }

                }, function error(response) {

                });

            }, function () {
                //else
            });

        } else {
            UIkit.notification({
                message: 'Please select a store!',
                status: 'warning',
                pos: 'top-right',
                timeout: 5000

            });


        }

    }

});