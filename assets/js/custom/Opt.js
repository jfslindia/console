/**
 * Created by targetman on 8/24/2019.
 */
var whatsapp_opt = angular.module('whatsapp_opt', ['chieffancypants.loadingBar', 'blockUI', 'ngMessages']);


whatsapp_opt.config(['cfpLoadingBarProvider',function (cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeSpinner = false;
}]);

whatsapp_opt.config(['blockUIConfig',function (blockUIConfig) {

    // Change the default overlay message
    //blockUIConfig.message = 'Please wait...';
    blockUIConfig.message = '';


    // Change the default delay to 100ms before the blocking is visible
    blockUIConfig.delay = 100;

}]);

whatsapp_opt.controller('opt',['$scope', '$http', 'cfpLoadingBar', 'blockUI', function ($scope, $http, cfpLoadingBar, blockUI) {

    $scope.start = function () {
        cfpLoadingBar.start();
    };

    $scope.complete = function () {
        cfpLoadingBar.complete();
    };



    $scope.opt_in_out=function(){
        var opt=$scope.opt;
        var customer_id=$('#field').attr('customer_id');

        blockUI.start();

        $http({
            method: "POST",
            url: base_url + "WAO/opt",
            data: {
                opt:opt,
                customer_id:customer_id
            }
        }).then(function success(response) {


            if (response.data.status == 'success') {

                if(opt==1){

                    UIkit.notification({
                        message: 'You will be receiving updates from Fabricspa on WhatsApp',
                        status: 'success',
                        pos: 'bottom-center',
                        timeout: 10000
                    });
                    /*setTimeout(function () {

                        window.location = "https://fabricspa.com";
                    }, 3000);*/

                }else{

                    UIkit.notification({

                        message: "You won't be receiving updates from Fabricspa on WhatsApp",
                        status: 'warning',
                        pos: 'bottom-center',
                        timeout: 10000
                    });
                   /* setTimeout(function () {

                        window.location = "https://fabricspa.com";
                    }, 3000);*/
                }

            }else{

                blockUI.stop();
                UIkit.notification({
                    message: 'Network seems busy. Please try again later!',
                    status: 'danger',
                    pos: 'bottom-center',
                    timeout: 3000
                });
            }
        });
    }
}]);