/*global navbarApp, Routing, window*/
navbarApp.controller('LoginController', ['$scope', '$http', function ($scope, $http) {
    'use strict';

    $scope.processForm = function () {

        $scope.loading = true;

        $http({
            method: 'POST',
            url: Routing.generate('fos_user_security_check'),
            data: $.param($scope.login),
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).success(function (data) {

            $scope.loading = false;

            if (data.success) {
                window.location.href = data.path;
            } else {
                $scope.error = data.message;
            }
        });
    };
}]);

