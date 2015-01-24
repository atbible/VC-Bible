(function (angular, window) {
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
                    this.search_results = ServiceFind.query({
                        version: this.context.version.name,
                        keywords: this.search.keywords
                    });

                    // Track user action
                    window.ga('send', 'event', {
                        'eventCategory': 'Search',
                        'eventAction': 'submit',
                        'eventValue': {version: this.context.version.name, keywords: this.search.keywords}
                    });
                };

                helper.clearSearchResults = function () {
                    this.search_results = [];
                };

                return helper;
            });

})(angular, window);
