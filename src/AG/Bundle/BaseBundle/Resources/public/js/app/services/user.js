angular.module('ag.services.user', ['ngResource'])

.factory('User', ['$resource',
    function($resource){
        return $resource(Routing.generate('rest_user_list') + ':userId', {}, {
            save: {method: 'POST', params:{userId:'new'}}
        });
    }
]);