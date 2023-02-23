
var welcome_app = angular.module('welcome_app', ['chieffancypants.loadingBar', 'blockUI', 'ngMessages']);


welcome_app.config(function (cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
});

welcome_app.config(function (blockUIConfig) {

    // Change the default overlay message
    //blockUIConfig.message = 'Please wait...';
    blockUIConfig.message = '';


    // Change the default delay to 100ms before the blocking is visible
    blockUIConfig.delay = 100;

});

welcome_app.directive("limitChars", [function () {
    return {
        restrict: "A",
        link: function (scope, elem, attrs) {
            var limit = parseInt(attrs.limitChars);
            angular.element(elem).on("keypress", function (e) {
                if (this.value.length == limit) e.preventDefault();
            });
        }
    }
}]);


welcome_app.directive('ngNumber', function () {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function (scope, element, attrs, ctrl) {
            ctrl.$parsers.push(function (input) {
                if (input == undefined) return ''
                var inputNumber = input.toString().replace(/[^0-9/]/g, '');
                if (inputNumber != input) {
                    ctrl.$setViewValue(inputNumber);
                    ctrl.$render();
                }
                return inputNumber;
            });
        }
    };
});


welcome_app.controller('welcome_form',['$scope','$http','cfpLoadingBar','blockUI',function ($scope, $http, cfpLoadingBar, blockUI) {

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    };

    $scope.area_list = [];


    $scope.register = function () {
        if ($scope.confirmation) {

            if($scope.reg_form.$valid) {
                blockUI.start();
                $http({
                    method: "POST",
                    url: base_url + "camp/register",
                    data: {
                        name: $scope.name,
                        dob: $scope.dob,
                        mobile_number: $scope.mobile_number,
                        email: $scope.email,
                        gender: $scope.gender,
                        pincode: $scope.pincode,
                        area_code: $scope.area_code,
                        house: $scope.house,
                        landmark: $scope.landmark

                    }

                }).then(function success(response) {
                    if(response.data.status=='success'){

                        $('#form_box').addClass('uk-hidden');
                        $('#result_box').removeClass('uk-hidden');
                        UIkit.notification({
                            message: 'Successfully registered!',
                            status: 'success',
                            pos: 'bottom-center',
                            timeout: 3000
                        });

                    }else{
                        UIkit.notification({
                            message: 'Failed to register. Please try again!',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 3000
                        });
                    }
                    blockUI.stop();
                });
            }else{
                UIkit.notification({
                    message: 'Please fill up necessary fields',
                    status: 'warning',
                    pos: 'bottom-center',
                    timeout: 3000
                });
            }
        }else{
            UIkit.notification({
                message: 'Please accept the T&C',
                status: 'warning',
                pos: 'bottom-center',
                timeout: 3000
            });
        }
    }

    $scope.load_area = function () {
        if ($scope.pincode.length == 6) {
            blockUI.start();
            $http({
                method: "POST",
                url: base_url + "camp/area_list",
                data: {
                    pincode: $scope.pincode
                }

            }).then(function success(response) {
                if(response.data.status=='success') {
                    if (response.data.area_list.length > 0) {
                        $scope.area_list = response.data.area_list;
                    }else{
                        $scope.area_list=[];
                        $scope.pincode=null;
                        UIkit.notification({
                            message: 'This pincode is non-serviceable',
                            status: 'danger',
                            pos: 'bottom-center',
                            timeout: 3000
                        });
                    }
                }else{
                    $scope.area_list=[];
                    $scope.pincode=null;
                    UIkit.notification({
                        message: 'This pincode is non-serviceable',
                        status: 'danger',
                        pos: 'bottom-center',
                        timeout: 3000
                    });
                }

                blockUI.stop();
            });
        }else{
            $scope.area_list= [];
        }
    }
}]);