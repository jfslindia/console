var jfsl_admin = angular.module('jfsl_admin', ['chieffancypants.loadingBar']);


jfsl_admin.config(function (cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
});



jfsl_admin.directive('loading', ['$http', function ($http) {
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

}]);

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
jfsl_admin.controller('search_panel', function ($scope, $http, $compile,cfpLoadingBar,//blockUI) {

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    };

    $scope.func=function(){

    //blockUI.start();
    $http({
        method: "POST",
        url: base_url+"admin_controller/change_password",
        data: {


        }
    }).then(function success(response) {

        if (response.data.status=='success') {

        //blockUI.stop();

        }else{

        }

    }
    });
});*/


jfsl_admin.controller('search_panel', function ($scope, $http, $compile,cfpLoadingBar) {

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    };

    $scope.search=function() {

        var search_with = $scope.search_with;
        var search_text = $scope.search_text;

        if (search_text) {

            //blockUI.start();
            $http({
                method: "POST",
                url: base_url + "Admin_Controller/search_panel",
                data: {

                    search_with: search_with,
                    search_text: search_text
                }
            }).then(function success(response) {

                if (response.data.status == 'success') {

                    //blockUI.stop();


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

                            $('#user_table_body').append('<tr>' +

                                '<td>' + response.data.result.user_details[i]['customer_id'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['date'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['name'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['mobile_number'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['email'] + ' </td>' +

                                '</tr>'
                            )
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

                            $('#user_table_body').append('<tr>' +

                                '<td>' + response.data.result.user_details[i]['customer_id'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['date'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['name'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['mobile_number'] + ' </td>' +
                                '<td>' + response.data.result.user_details[i]['email'] + ' </td>' +
                                '</tr>'
                            )

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

                    //blockUI.stop();

                    UIkit.notification({
                        message: 'Failed',
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

                    //blockUI.stop();

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

                    //blockUI.stop();

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
                message: 'Search text can not be empty',
                status: 'danger',
                pos: 'bottom-center',
                timeout: 1000
            });
        }
    }
});

jfsl_admin.controller('change_passwd', function ($scope, $http, $compile, cfpLoadingBar) {

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

                    //blockUI.start();

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

                            //blockUI.stop();

                            UIkit.notification({
                                message: 'Failed',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }

                        else if (response.data.status == 'wrong_password') {

                            //blockUI.stop();

                            UIkit.notification({
                                message: 'Wrong Password',
                                status: 'warning',
                                pos: 'bottom-center',
                                timeout: 1000
                            });
                        }


                    });

                } else {
                    //blockUI.stop();
                    UIkit.notification({
                        message: 'Password can not be empty',
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 1000
                    });

                }
            }

            else {

                //blockUI.stop();

                UIkit.notification({
                    message: 'Enter the same password',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 1000
                });
            }


        } else {

            //blockUI.stop();

            UIkit.notification({
                message: 'Enter a new password',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 1000
            });

        }
    }
});

jfsl_admin.controller('qc', function ($scope, $http, $compile, cfpLoadingBar) {
    $scope.mode = 'default';

    $scope.results = [];
    $scope.get_qc_users = get_qc_users();

    $scope.orderByField = 'title';
    $scope.reverseSort = false;


    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    }

    $scope.add_qc_user = function () {
        $scope.mode = 'add';
        $('#qc_user_modal_details').removeAttr('index');
        $('#qc_user_modal_details').removeAttr('qc_user_id');

        $('#modal-title').html('Add a QC user');

        $scope.form_name = '';
        $scope.form_contactno = '';
        $scope.form_privilege = '';
        $scope.form_passwd = '';


        UIkit.modal("#qc_user_modal").show();

    }

    $scope.save_qc_user = function () {

        var qc_user_name = $scope.form_name;
        var qc_user_contactno = $scope.form_contactno;

        var qc_user_passwd = $scope.form_passwd;


        if (qc_user_name && qc_user_contactno && qc_user_passwd) {

            UIkit.modal("#qc_user_modal").hide();

            $http({
                method: "POST",
                url: base_url + "Admin_Controller/save_qc_user",
                data: {
                    mode: $scope.mode,
                    id: $('#qc_user_modal_details').attr('qc_user_id'),
                    name: qc_user_name,
                    contactno: qc_user_contactno,
                    passwd: qc_user_passwd


                }
            }).then(function success(response) {

                if (response.data.length == 1 && !response.data.status) {

                    UIkit.modal("#qc_user_modal").hide();

                    var qc_user = response.data[0];

                    if ($scope.mode == 'add') {

                        $scope.results.qc_users.push(qc_user);

                    } else if ($scope.mode == 'edit') {

                        var index = $('#qc_user_modal_details').attr('index');
                        $scope.results.qc_users[index] = qc_user;
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

    function get_qc_users() {
        $http({
            method: "POST",
            url: base_url + "admin_controller/get_qc_users"

        }).then(function success(response) {

            if (response.data.length > 0) {
                $scope.results = {
                    qc_users: response.data
                };
            } else {
                $scope.results = {
                    qc_users: []
                };
            }

        }, function error(response) {
            //console.log(response)
        });
    }

    $scope.edit_qc_user = function (id, index) {

        $('#qc_user_modal_details').attr('index', index);
        $('#qc_user_modal_details').attr('qc_user_id', id);


        $scope.mode = 'edit';
        $('#modal-title').html('Edit : ' + $scope.results.qc_users[index].Name);

        $scope.form_name = $scope.results.qc_users[index].Name;
        $scope.form_contactno = $scope.results.qc_users[index].Phone;

        $scope.form_passwd = $scope.results.qc_users[index].Password;


        UIkit.modal("#qc_user_modal").show();
    }

    $scope.delete_qc_users = function () {

        var selected_qc_users = [];

        $("input[name='qc_user_row']:checked").each(function () {
            selected_qc_users.push($(this).val());
        });

        if (selected_qc_users.length && selected_qc_users) {

            UIkit.modal.confirm('Do you want to delete this qc_user?').then(function () {
                $http({
                    method: "post",
                    data: {
                        "selected_qc_users": selected_qc_users
                    },
                    url: base_url + "Admin_Controller/delete_qc_users"
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
                message: 'Please select a qc_user!',
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