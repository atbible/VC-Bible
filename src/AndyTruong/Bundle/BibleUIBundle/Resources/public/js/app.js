(function(angular, $) {
    var ctrl_arguments = ['$scope', '$location', 'ServiceVersions', 'ServiceBooks', 'ServiceVerses', 'ServiceFind'];
    ctrl_arguments.push(function($scope, $location, ServiceVersions, ServiceBooks, ServiceVerses, ServiceFind) {
        $scope.context = $scope.input = {version: null, book: null, chapter: 1};

        // Parse input
        var path = $location.path().match(/(\w+)\/(\w+)\/(\w+)/);
        if (path) {
            $scope.input = {version: path[1], book: path[2], chapter: path[3]};
            $scope.context.chapter = parseInt(path[3]);
        }

        // on change callbacks
        $scope.change = {
            version: function() {
                $scope.change.chapter();
            },
            book: function(chapter) {
                $scope.chapters = [];
                for (var i = 1; i <= $scope.context.book[2]; i++)
                    $scope.chapters.push(i);
                $scope.context.chapter = typeof chapter !== 'undefined' ? chapter : 1;
                $scope.change.chapter();
            },
            chapter: function() {
                var params = {'version': $scope.context.version.name, 'book': $scope.context.book[0], 'chapter': $scope.context.chapter};
                $scope.verses = ServiceVerses.query(params);
                $location.path('/' + $scope.context.version.name + '/' + $scope.context.book[0] + '/' + $scope.context.chapter);
            },
            search_results: function(version, book, chapter) {
                for (var index in $scope.versions)
                    if (version === $scope.versions[index].name)
                        $scope.context.version = $scope.versions[index]

                $scope.context.book = $scope.books[book - 1];
                $scope.context.chapter = parseInt(chapter);
                $scope.change.chapter();
            }
        };

        // Query for Vsersions
        $scope.versions = ServiceVersions.query(function() {
            for (var version in $scope.versions)
                if ($scope.input.version === $scope.versions[version].name)
                    $scope.context.version = $scope.versions[version]

            if (!$scope.context.version)
                $scope.context.version = $scope.versions[0];

            // Query for books
            $scope.books = ServiceBooks.query(function() {
                for (var book in $scope.books)
                    if ($scope.input.book === $scope.books[book][0])
                        $scope.context.book = $scope.books[book];

                if (!$scope.context.book)
                    $scope.context.book = $scope.books[0];

                $scope.change.book($scope.context.chapter);
            });
        });

        $scope.openSearchDialog = function() {
            $scope.search = {
                version: $scope.context.version.name,
                keywords: '',
                results: []
            };

            try {
                $('#bibleSearchForm').dialog('destroy');
            }
            catch (e) {
            }

            // blur from fake widget
            $('input[type="text"]').blur();

            // Open the dialog
            $('#bibleSearchForm')
                    .dialog({position: {my: 'center top-250'}, minWidth: 450, minHeight: 250})
                    .dialog('open')
                    .find('input[type="text"]').focus();
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
