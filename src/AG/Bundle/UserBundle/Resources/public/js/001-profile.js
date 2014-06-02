/*global profile, Routing, window*/
profile.controller('ProfileController', ['$scope', '$modal', '$timeout', 'User', function($scope, $modal, $timeout, User) {
        'use strict';

        var timeout = null,
                showSpinner = function() {
                    $scope.saved = false;
                    $scope.spinner = true;
                },
                showSaved = function() {
                    $scope.spinner = false;
                    $scope.saved = true;

                    $timeout(function() {
                        $scope.saved = false;
                    }, 1000);
                },
                saveUpdates = function() {
                    var user = new User($scope.user);
                    showSpinner();

                    user.$update(function(response) {
                        if (response.success) {
                            showSaved();
                        }
                    });
                };

        $scope.password = '';
        $scope.newPhone = 'Добавить телефон';

        $scope.user = {};

        $scope.$watch('user', function(newVal, oldVal) {
            if (newVal !== oldVal) {
                if (timeout) {
                    $timeout.cancel(timeout);
                }
                timeout = $timeout(saveUpdates, 1000);
            }
        }, true);

        $scope.processChangePasswordForm = function() {
            var user = new User({'password': $scope.password});

            $scope.loadingPassword = true;

            user.$password(function(response) {

                $scope.loadingPassword = false;

                if (response.success) {
                    $scope.changePassword = false;
                    $scope.success = response.message;

                    $timeout(function() {
                        $scope.success = false;
                    }, 5000);
                } else {
                    $scope.error = response.message;
                }
            });
        };

        $scope.processChangePhoneForm = function() {

            $scope.loadingPhone = true;

            var user = new User({'tmpPhone': $scope.phone});

            user.$tmpPhone(function(response) {
                if (response.success) {
                    $scope.loadingPhone = false;

                    var phoneConfirmationModal = $modal.open({
                        templateUrl: Routing.generate('user_profile_phone_modal'),
                        controller: phoneConfirmationModalController,
                        size: 'sm'
                    });
                    phoneConfirmationModal.result.then(function(phone, message) {
                        $scope.error = '';
                        $scope.success = message;
                        $scope.changePhone = false;
                        $scope.newPhone = phone;
                        $scope.phone = '';
                        
                    }, function() {
                        return;
                    });

                } else {
                    $scope.error = 'Упс! Что-то пошло не так. Оновите страницу.';
                }
            });
        };

    }]);

var phoneConfirmationModalController = function($scope, $modalInstance, User) {

    $scope.input = {};
    $scope.input.loadingConfirmation = false;
    $scope.input.error = false;

    $scope.cancel = function() {
        $modalInstance.dismiss('cancel');
    };

    $scope.processPhoneConfirmationForm = function() {

        $scope.input.loadingConfirmation = true;

        var user = new User({'code': $scope.input.code});

        user.$setPhone(function(response) {
            $scope.input.loadingConfirmation = false;

            if (response.success) {
                $modalInstance.close(response.phone, response.message);
            } else {
                $scope.input.error = response.message;
                ;
            }
        });
    };
};