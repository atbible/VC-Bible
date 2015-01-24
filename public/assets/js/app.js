(function (angular, window) {
    var mods = ['ui.bootstrap', 'BibleUIServices', 'BibleUISearchHelper', 'BibleUIDirectives', 'ngSanitize'];
    var args = ['$scope', '$location', '$http', 'baseURL', 'ServiceVersions', 'ServiceBooks', 'ServiceVerses', '$uiSearchHelper', '$sce'];

    if ((typeof isAdmin !== 'undefined') && isAdmin) {
        mods.push('xeditable');
    }

    args.push(function ($scope, $location, $http, baseURL, ServiceVersions, ServiceBooks, ServiceVerses, $uiSearchHelper, $sce) {
        $scope.context = $scope.input = {version: null, book: null, chapter: 1};
        angular.extend($scope, $uiSearchHelper);

        // Parse input
        var path = $location.path().match(/(\w+)\/(\w+)\/(\w+)/);
        if (path) {
            $scope.input = {version: path[1], book: path[2], chapter: path[3]};
            $scope.context.chapter = parseInt(path[3]);
        }

        // on change callbacks
        $scope.change = {
            version: function () {
                $scope.change.chapter();
            },
            book: function (chapter) {
                $scope.chapters = [];
                for (var i = 1; i <= $scope.context.book[2]; i++)
                    $scope.chapters.push(i);
                $scope.context.chapter = typeof chapter !== 'undefined' ? chapter : 1;
                $scope.change.chapter();
            },
            chapter: function () {
                var params = {'version': $scope.context.version.name, 'book': $scope.context.book[0], 'chapter': $scope.context.chapter};
                var path = '/' + $scope.context.version.name + '/' + $scope.context.book[0] + '/' + $scope.context.chapter;
                $scope.verses = ServiceVerses.query(params);
                $location.path(path);

                // Track page view
                window.ga('send', 'pageview', {page: path});
            },
            search_results: function (version, book, chapter) {
                for (var index in $scope.versions)
                    if (version === $scope.versions[index].name)
                        $scope.context.version = $scope.versions[index];

                $scope.context.book = $scope.books[book - 1];
                $scope.context.chapter = parseInt(chapter);
                $scope.change.chapter();

                // Track user action
                window.ga('send', 'event', {
                    eventCategory: 'Search',
                    eventAction: 'SelectResult',
                    eventValue: {
                        version: version,
                        book: $scope.context.chapter,
                        chapter: chapter
                    }
                });
            }
        };

        // Query for Versions
        $scope.versions = ServiceVersions.query(function () {
            for (var version in $scope.versions)
                if ($scope.input.version === $scope.versions[version].name)
                    $scope.context.version = $scope.versions[version];

            if (!$scope.context.version)
                $scope.context.version = $scope.versions[0];

            // Query for books
            $scope.books = ServiceBooks.query(function () {
                for (var book in $scope.books)
                    if ($scope.input.book === $scope.books[book][0])
                        $scope.context.book = $scope.books[book];

                if (!$scope.context.book)
                    $scope.context.book = $scope.books[0];

                $scope.change.book($scope.context.chapter);
            });
        });

        $scope.renderVerseBody = function (body) {
            for (var i in $scope.books)
                if ($scope.context.book[0] === $scope.books[i][0])
                    break;

            var prefix = i < 40 ? 'H' : 'G';

            return $sce.trustAsHtml(body
                    .replace(/\[(\d+)\]/gm, '<span class="strong strong1"><a href="http://studybible.info/strongs/' + prefix + '$1">[$1]</a></span>')
                    .replace(/\((\d+)\)/gm, '<span class="strong strong2"><a href="http://studybible.info/strongs/' + prefix + '$1">[$1]</a></span></span>'));
        };

        // Admin
        $scope.updateVerse = function (id, writing) {
            $http
                    .put(baseURL + '/verse/' + id, {id: id, writing: writing})
                    .success(function ($data) {
                    });
        };
    });

    var app = angular
            .module('BibleUI', mods)
            .filter('range', function () {
                return function (input, min, max) {
                    min = parseInt(min); //Make string input int
                    max = parseInt(max);
                    for (var i = min; i < max; i++)
                        input.push(i);
                    return input;
                };
            })
            .filter('StrongNumber', function () {
                return function (input) {
                    return input.replace(/(\[\d+\])/, '<span class="strong strong1">$1</span>');
                };
            })
            .controller('BibleUIController', args);

    if ((typeof isAdmin !== 'undefined') && isAdmin) {
        app.run(function (editableOptions) {
            editableOptions.theme = 'bs3';
        });
    }

})(angular, window);
