/*global profile, Routing, window*/
profile.controller('CarsController', ['$scope', '$http', function($scope, $http) {
        'use strict';

        $scope.cars = [];
        $scope.marks = [];
        $scope.models = [];
        $scope.deleting = false;

        // set years
        var year = new Date().getFullYear();
        $scope.years = [{text: year}];
        for (var i = 0; i < 50; i++) {
	   year = year - 1;
           $scope.years.push({text: year});
	}

        $http.get(Routing.generate('rest_user_cars')).success(function(data) {
            $scope.cars = data;
        });

        $scope.delete = function(id, index) {
            
            if (!confirm("Точно удалить автомобиль?")) {
                return null;
            }
            
            $scope.deleting = id;

            $http.delete(Routing.generate('rest_user_car_delete', {id: id})).success(function(data) {

                $scope.deleting = false;
                $scope.error = false;

                if (data.success) {
                    $scope.cars.splice(index, 1);
                } else {
                    $scope.error = data.message;
                }
            });
        };
        
        $scope.processCarNumber = function() {
            
            $scope.processCarNumberButton = true;
            $scope.$apply();

            var data = {number: $scope.number};
            
            $http.post(Routing.generate('rest_user_car_create'), data).success(function(data) {
                $scope.processCarNumberButton = false;
                if (data.success) {
                    $scope.cars.push(data.car);
                    checkLength();
                    $scope.showAddCarForm = false;
                    $scope.num1 = $scope.num2 = $scope.num3 = $scope.num4 = null;
                } else {
                    $scope.error = data.message;
                }
            });
        };
        
        $scope.saveCar = function(data, id) {
            $scope.editing = id;
            $http.put(Routing.generate('rest_user_car_update', {id: id}), data).success(function(data) {
                $scope.editing = false;
                if (!data.success) {
                    $scope.error = data.message;
                }
            });
        };
        
        $scope.loadMarks = function() {
            return $scope.marks.length ? null : $http.get(Routing.generate('rest_user_car_marks')).success(function(data) {
                $scope.marks = data;
            });
        };
        
        $scope.loadModels = function() {
            return $scope.models.length ? null : $http.get(Routing.generate('rest_user_car_models')).success(function(data) {
                $scope.models = data;
            });
        };
        
    }]);
