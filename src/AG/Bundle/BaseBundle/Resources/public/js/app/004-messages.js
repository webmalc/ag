/*global messages, Routing*/
messages.controller('MessagesController', ['$scope', '$http', function ($scope, $http) {
    'use strict';
    
    $scope.clearNotifications = function() {
        
        $scope.notifier = false;
        
        $http.get(Routing.generate('user_profile_clear_notifications'));
    };
}]);

