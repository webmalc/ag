/*global navbar, Routing, window*/
navbar.controller('StatsController', ['$scope', '$modal', function($scope, $modal) {
        'use strict';

        $scope.show = function() {
            var messagesModal = $modal.open({
                templateUrl: Routing.generate('user_stats_modal'),
                size: 'sm'
            });
        };
    }]);

