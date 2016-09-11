angular.module('TrackerApp')
    .directive('menu', ['$window', '$injector', '$location', function($window, $injector, $location) {

        return {
            restrict: 'E',
            replace: true,
            link: function(scope, element, attrs) {
                scope.loggedIn = $injector.get('AuthService').isLoggedIn();
                scope.isAdmin = $injector.get('AuthService').isAdmin();
                scope.isTrader = $injector.get('AuthService').isTrader();
                scope.dropdown = false;
                scope.user = $injector.get('AuthService').currentUser();
                scope.$watch($injector.get('AuthService').currentUser, function(newValue, oldValue) {
                    if (newValue !== oldValue) {
                        scope.user = newValue;
                    }
                }, true);

                scope.$watch($injector.get('AuthService').isLoggedIn, function(newValue, oldValue) {
                    if (newValue !== oldValue) {
                        scope.loggedIn = newValue;
                    }
                }, true);

                scope.$watch($injector.get('AuthService').isAdmin, function(newValue, oldValue) {
                    if (newValue !== oldValue) {
                        scope.isAdmin = newValue;
                    }
                }, true);

                scope.$watch($injector.get('AuthService').isTrader, function(newValue, oldValue) {
                    if (newValue !== oldValue) {
                        scope.isTrader = newValue;
                    }
                }, true);

                scope.getClass = function (path) {
                    return ($location.path().substr(0, path.length) === path) ? 'active' : '';
                }
            },
            templateUrl: function(elem,attrs) {
                return 'partials/menu.html'
            }
        }
    }]);