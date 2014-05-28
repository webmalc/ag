angular.module('ag.services.user', ['ngResource'])

.factory('User', ['$resource',
    function($resource){
        return $resource(Routing.generate('rest_user_list') + ':userId', {}, {
            save: {method: 'POST', params:{userId:'new'}},
            update: {method: 'POST', params:{userId:'update'}},
            password: {method: 'POST', params:{userId:'password/change'}},
            tmpPhone: {method: 'POST', params:{userId:'profile/phone/check'}},
            setPhone: {method: 'POST', params:{userId:'profile/phone/set'}}
        });
    }
]);