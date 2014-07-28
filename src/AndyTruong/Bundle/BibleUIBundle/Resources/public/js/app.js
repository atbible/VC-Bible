(function(angular, $) {
    var ctrl_arguments = ['$scope', '$location', 'ServiceVersions', 'ServiceBooks', 'ServiceVerses', 'ServiceFind'];
    ctrl_arguments.push(function($scope, $location, ServiceVersions, ServiceBooks, ServiceVerses, ServiceFind) {
        $scope.context = $scope.input = {version: null, book: null, chapter: null};

        // Parse input
        var path = $location.path().match(/(\w+)\/(\w+)\/(\w+)/);
        if (path) {
            $scope.context.version = path[1];
            $scope.context.book = path[2];
            $scope.context.chapter = path[3];
        }

        // on change callbacks
        $scope.change = {
            version: function() {
                $scope.change.chapter();
            },
            book: function() {
                $scope.chapters = [];
                for (var i = 1; i <= $scope.context.book[2]; i++)
                    $scope.chapters.push(i);
                $scope.context.chapter = 1;
                $scope.change.chapter();
            },
            chapter: function() {
                var params = {'version': $scope.context.version.name, 'book': $scope.context.book[0], 'chapter': $scope.context.chapter};
                $scope.verses = ServiceVerses.query(params);
                $location.path('/' + $scope.context.version.name + '/' + $scope.context.book[0] + '/' + $scope.context.chapter);
            }
        };

        // Query for Vsersions
        $scope.versions = ServiceVersions.query(function() {
            $scope.context.version = $scope.versions[0];
        });

        // Query for books
        $scope.books = ServiceBooks.query(function() {
            if (!$scope.context.book)
                $scope.context.book = $scope.books[0];

            $scope.change.book();
        });

        $scope.openSearchDialog = function() {
            $scope.search = {
                version: $scope.context.version.name,
                keywords: '',
                results: []
            };
            $('#bibleSearchForm').dialog({position: {my: 'center top-250'}, minWidth: 450, minHeight: 250});
        };

        $scope.doSearch = function() {
            $scope.search.results = ServiceFind.query({version: $scope.search.version, keywords: $scope.search.keywords});
        };
    });

    angular
            .module('BibleUI', ['ui.bootstrap', 'BibleUIServices', 'BibleUIDirectives'])
            .filter('range', function() {
                return function(input, min, max) {
                    min = parseInt(min); //Make string input int
                    max = parseInt(max);
                    for (var i = min; i < max; i++)
                        input.push(i);
                    return input;
                };
            })
            .controller('BibleUIController', ctrl_arguments);

})(angular, jQuery);
