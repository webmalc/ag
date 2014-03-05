/*global navbarApp, Routing, window*/
navbarApp.controller('LoginController', ['$scope', '$http', function ($scope, $http) {
    'use strict';

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
                $scope.success = false;
                $scope.error = data.message;
            }
        });
    };
    $scope.processResetForm = function () {

        $scope.error = false;
        $scope.success = 'Новый пароль выслан вам';
        $scope.form.password = false;
    };
}]);

