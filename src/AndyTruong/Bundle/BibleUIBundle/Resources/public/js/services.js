(function(angular) {

    angular
            .module('BibleUIServices', ['ngResource'])
            .value('baseURL', '/')
            .factory('ServiceVersions', ['$resource', function($resource) {
                    return $resource('/versions.json', {}, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            .factory('ServiceBooks', ['$resource', function($resource) {
                    return $resource('/books.json', {}, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            .factory('ServiceVerses', ['$resource', function($resource) {
                    var params = {
                        ':version': '@version',
                        ':book': '@book',
                        ':chapter': '@chapter'
                    };

                    return $resource('/:version/:book/:chapter.json', params, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            ;

})(angular);
