(function (angular) {
    angular
            .module('BibleUISearchHelper', ['BibleUIServices'])
            .factory('ServiceFind', ['$resource', 'baseURL', function ($resource, baseURL) {
                    return $resource(baseURL + '/find/:version/:keywords', {}, {
                        query: {method: 'GET', isArray: true}
                    });
                }])
            .factory('$uiSearchHelper', function (ServiceFind) {
                var helper = {};

                helper.doSearch = function () {
                    helper.search_results = ServiceFind.query({
                        version: helper.context.version.name,
                        keywords: helper.search.keywords
                    });
                };

                helper.clearSearchResults = function () {
                    helper.search_results = [];
                };

                return helper;
            });

})(angular);
