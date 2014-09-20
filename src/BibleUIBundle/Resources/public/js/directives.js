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
            .directive('bibleNav', function(bundleRoot) {
                return {
                    restrict: 'E',
                    templateUrl: bundleRoot + '/templates/ui.nav.html'
                };
            });

})(angular);
