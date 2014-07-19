(function(angular) {

    angular
            .module('BibleUIDirectives', [])
            .value('bundleRoot', '/bundles/andytruongbibleui')
            .directive('bibleUi', function(bundleRoot) {
                return {
                    restrict: 'E',
                    templateUrl: bundleRoot + '/templates/ui.html'
                };
            })
            .directive('bibleNavigation', function(bundleRoot) {
                return {
                    restrict: 'E',
                    templateUrl: bundleRoot + '/templates/ui-navigation.html'
                };
            })
            .directive('bibleReadingArea', function(bundleRoot) {
                return {
                    restrict: 'E',
                    templateUrl: bundleRoot + '/templates/ui-reading-area.html'
                };
            });

})(angular);
