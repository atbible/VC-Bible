(function(angular) {
    var ctrl_arguments = ['$scope', '$location', 'ServiceVersions', 'ServiceBooks', 'ServiceVerses'];
    ctrl_arguments.push(function($scope, $location, ServiceVersions, ServiceBooks, ServiceVerses) {
        $scope.context = $scope.input = {version: null, book: null, chapter: null};

        // Parse input
        var path = $location.path().match(/(\w+)\/(\w+)\/(\w+)/);
        if (path) {
            $scope.input = {version: path[1], book: path[2], chapter: path[3]};
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
                $scope.context.chapter = parseInt($scope.input.chapter ? $scope.input.chapter : $scope.chapters[0]);
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

        // Queyr for books
        $scope.books = ServiceBooks.query(function() {
            for (var book in $scope.books)
                if ($scope.input.book === $scope.books[book][0])
                    $scope.context.book = $scope.books[book];

            if (!$scope.context.book)
                $scope.context.book = $scope.books[0];

            $scope.change.book();
        });
    });

    angular
            .module('BibleUI', ['ui.bootstrap', 'BibleUIServices', 'BibleUIDirectives'])
            .controller('BibleUIController', ctrl_arguments);

})(angular);
