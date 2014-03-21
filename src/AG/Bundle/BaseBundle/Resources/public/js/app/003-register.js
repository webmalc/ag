/*global navbar, Routing, window*/
navbar.controller('RegisterController', ['$scope', 'User', function ($scope, User) {
    'use strict';

    $scope.error = '';

    $scope.processRegisterForm = function () {

        $scope.loading = true;

        var user = new User({email: $scope.email}),
            route = '';

        user.$save(function (response) {
            $scope.loading = false;

            if (response.success) {

                route = Routing.generate('fos_user_security_login');

                if (window.location.pathname === route) {
                    window.location.href =  route + '#?email=' + response.message;
                    window.location.reload(true);
                } else {
                    window.location.href =  route + '#?email=' + response.message;
                }
            } else {
                $scope.error = 'Упс! ' + response.message;
            }
        });
    };
}]);

