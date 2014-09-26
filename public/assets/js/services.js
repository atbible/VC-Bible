(function (angular) {

    angular
            .module('BibleUIServices', ['ngResource'])
            .value('baseURL', '/index.php/bible')
            .factory('ServiceVersions', ['$resource', 'baseURL', function ($resource, baseURL) {
                    return $resource(baseURL + '/versions.json', {}, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            .factory('ServiceBooks', ['$resource', 'baseURL', function ($resource, baseURL) {
                    return $resource(baseURL + '/books.json', {}, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            .factory('ServiceVerses', ['$resource', 'baseURL', function ($resource, baseURL) {
                    var params = {
                        ':version': '@version',
                        ':book': '@book',
                        ':chapter': '@chapter'
                    };

                    return $resource(baseURL + '/:version/:book/:chapter.json', params, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            .factory('ServiceFind', ['$resource', 'baseURL', function ($resource, baseURL) {
                    return $resource(baseURL + '/find/:version/:keywords', {}, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            ;

})(angular);
