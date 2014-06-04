/*global profile, Routing, window*/
search.controller('SearchController', ['$scope', '$http', '$modal', function($scope, $http, $modal) {
        'use strict';

        $scope.noResults = false;
        $scope.results = false;
        $scope.car = false;
        $scope.success = false;
        $scope.error = false;
        $scope.sending = false;

        $scope.processCarNumber = function() {
            
            $scope.$apply();
            $scope.noResults = false;
            $scope.results = false;
            $scope.success = false;
            $scope.error = false;
            $scope.sending = false;
            $scope.processCarNumberButton = true;
            $scope.car = false;
            
            var data = {1: $scope.num1, 2: $scope.num2, 3: $scope.num3, 4: $scope.num4};
            
            $http.post(Routing.generate('rest_user_car_search'), data).success(function(data) {
                
                $scope.processCarNumberButton = false;
                
                if (data.success) {
                    $scope.results = true;
                    $scope.car = data.car;
                } else {
                    $scope.noResults = true;
                }
            });

        };
        
        $scope.sendMessage = function(car, type) {
            $scope.results = false;
            $scope.noResults = false;
            $scope.success = false;
            $scope.error = false;
            $scope.sending = true;
            $scope.num1 = $scope.num2 = $scope.num3 = $scope.num4 = '';
            
            $http.post(Routing.generate('rest_user_car_send_message', {id: car.id, type: type})).success(function(data) {
                
                $scope.sending = false;
                
                if (data.success) {
                    $scope.success = data.message;
                } else {
                    $scope.error = data.message;
                }
            });
        };

        $scope.showMessageModal = function() {
            var messagesModal = $modal.open({
                templateUrl: Routing.generate('car_search_messages_modal'),
            });
        };
        
        $scope.registrationAlert = function() {
            var registrationModal = $modal.open({
                templateUrl: Routing.generate('car_search_registration_modal'),
            });
        };

    }]);
