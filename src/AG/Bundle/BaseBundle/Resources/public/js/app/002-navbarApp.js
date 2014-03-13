/*global navbarApp, Routing, window*/
navbarApp.controller('LoginController', ['$scope', '$http', '$timeout', function ($scope, $http, $timeout) {
    'use strict';

    $scope.clearAlerts = function () {
        $scope.success = '';
        $scope.error = '';
    };

    $scope.processLoginForm = function () {

        $scope.loading.login = true;

        $http({
            method: 'POST',
            url: Routing.generate('fos_user_security_check'),
            data: $.param($scope.login),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (data) {

            $scope.loading.login = false;

            if (data.success) {
                window.location.href = data.path;
            } else {
                $scope.clearAlerts();
                $scope.error = 'Упс! ' + data.message;
            }
        });
    };
    $scope.processResetForm = function () {

        $scope.loading.password = true;

        $http.post(Routing.generate('password_resseting_request'), $scope.remind)
             .success(function (data) {
                
                $scope.loading.password = false;
                if (data.success) {
                    $scope.clearAlerts()
                    $scope.success = 'Ура! ' + data.message;
                    $scope.form.password = false;
                    $timeout(function () { $scope.clearAlerts(); }, 10000);
                } else {
                    $scope.clearAlerts();
                    $scope.error = 'Упс! ' + data.message;
                }
             });
        
        
    };
}]);

