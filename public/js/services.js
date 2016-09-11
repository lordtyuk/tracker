var host = 'http://api.tracker.dev';
//var host = 'http://api.etelminta-reg.hu';

window.host = host;

angular.module('TrackerApp.services', []).factory('Api', ['$resource',
    function($resource) {
        return {
            Reseller: $resource(host+'/v1/resellercodes'),
            Product: $resource(host+'/v1/products/:id', {
                id: '@id'
            }, {
                'update': {
                    method: 'PUT'
                },
                'get': {
                    method:'GET',
                    transformResponse: [
                        function(data, headersGetter) {

                            var content = angular.fromJson(data);

                            content.producedAt = new Date(content.producedAt);
                            content.storedAt = new Date(content.storedAt);
                            content.useBefore = new Date(content.useBefore);

                            return content;
                        }
                    ]
                },
                removeFile:{
                    url: host+'/v1/products/:id/files/:fileId',
                    method: 'DELETE',
                    params:{
                        fileId: '@fileId'
                    }
                },
                'userProducts': {
                    isArray: true,
                    method:'GET',
                    url: host+'/v1/users/:id/products',
                    transformResponse: [
                        function(data, headersGetter) {

                            var content = angular.fromJson(data);
                            for(var i in content) {
                                var _content = content[i];
                                _content.producedAt = new Date(_content.producedAt);
                                _content.storedAt = new Date(_content.storedAt);
                                _content.useBefore = new Date(_content.useBefore);
                            }
                            return content;
                        }
                    ]
                },
                'my': {
                    isArray: true,
                    method:'GET',
                    url: host+'/v1/users/me/products',
                    transformResponse: [
                        function(data, headersGetter) {

                            var content = angular.fromJson(data);
                            for(var i in content) {
                                var _content = content[i];
                                _content.producedAt = new Date(_content.producedAt);
                                _content.storedAt = new Date(_content.storedAt);
                                _content.useBefore = new Date(_content.useBefore);
                            }
                            return content;
                        }
                    ]
                },
                'subscribe': {
                    method:'POST',
                    url: host+'/v1/users/me/products'
                }
            }),
            User:  $resource(host+'/v1/users/:id', {
                id: '@id'
            }, {
                'authenticate': {
                    method:'POST',
                    url: host+'/v1/oauth2/token'
                },
                'me': {
                    method:'GET',
                    url: host+'/v1/users/me'
                },
                'unauthenticate': {
                    method:'GET',
                    url: host+'/v1/oauth2/revoke'
                },
                'buyers': {
                    isArray: true,
                    method:'GET',
                    url: host+'/v1/users/me/buyers'
                }
            }),
            File:  $resource(host+'/v1/files/:id', {
                id: '@id'
            })
        };
    }]);


angular.module('TrackerApp.services').service('PagerService',['$window',function($window){
    // service definition
    var service = {};

    service.GetPager = GetPager;

    return service;

    // service implementation
    function GetPager(totalItems, currentPage, pageSize) {
        // default to first page
        currentPage = currentPage || 1;

        // default page size is 10
        pageSize = pageSize || 10;

        // calculate total pages
        var totalPages = Math.ceil(totalItems / pageSize);

        var startPage, endPage;
        if (totalPages <= 10) {
            // less than 10 total pages so show all
            startPage = 1;
            endPage = totalPages;
        } else {
            // more than 10 total pages so calculate start and end pages
            if (currentPage <= 6) {
                startPage = 1;
                endPage = 10;
            } else if (currentPage + 4 >= totalPages) {
                startPage = totalPages - 9;
                endPage = totalPages;
            } else {
                startPage = currentPage - 5;
                endPage = currentPage + 4;
            }
        }

        // calculate start and end item indexes
        var startIndex = (currentPage - 1) * pageSize;
        var endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);

        // create an array of pages to ng-repeat in the pager control
        var pages = _.range(startPage, endPage + 1);

        // return object with all pager properties required by the view
        return {
            totalItems: totalItems,
            currentPage: currentPage,
            pageSize: pageSize,
            totalPages: totalPages,
            startPage: startPage,
            endPage: endPage,
            startIndex: startIndex,
            endIndex: endIndex,
            pages: pages
        };
    }
}]);

angular.module('TrackerApp.services').service('popupService',['$window',function($window){
    this.showPopup=function(message){
        return $window.confirm(message); //Ask the users if they really want to delete
    }
}]);

angular.module('TrackerApp.services').service('AuthService', ['$injector', '$window', function($injector, $window) {
    var currentUser = null;

    return {
        login: function(user) {
            return promise = new Promise(function(resolve, reject) {
                user.client_id = 1;
                user.client_secret = 1;
                user.grant_type = 'password';
                user.$authenticate(function() {
                }).then(function(e) {
                    if(e.access_token) {
                        var expires = new Date();
                        expires.setSeconds(expires.getSeconds() + e.expires_in);
                        $window.localStorage.setItem('token', e.access_token);
                        $window.localStorage.setItem('refresh_token', e.refresh_token);
                        $window.localStorage.setItem('token_expires', expires);

                        user.$me(function () {
                        }).then(function (e) {
                            currentUser = e;
                        });

                        $injector.get('$state').go('products'); //
                        resolve();
                    } else {
                        reject("Hibás email név vagy jelszó");
                    }
                });
            });
        },
        wake: function() {
            return promise = new Promise(function(resolve, reject) {
                if(!$window.localStorage.getItem('token')) {
                    reject();
                }

                var Api = $injector.get('Api');
                var user = new Api.User();

                user.$me(function() {
                }).then(function (e) {
                    if(e.id) {
                        currentUser = e;
                        if(!$injector.get('$state').current)
                            $injector.get('$state').go('myProducts');
                    }
                    resolve();
                }, function() {
                    $window.localStorage.removeItem('token');
                    $window.localStorage.removeItem('refresh_token');
                    $window.localStorage.removeItem('token_expires');

                    reject();
                });

            });
        },
        logout: function() {

            var Api = $injector.get('Api');
            var user = new Api.User();

            user.$unauthenticate(function() {

                $window.localStorage.removeItem('token');
                $window.localStorage.removeItem('refresh_token');
                $window.localStorage.removeItem('token_expires');

                currentUser = null;

                $injector.get('$state').go('login'); //
            });
        },
        isLoggedIn: function() { return currentUser != null; },
        currentUser: function() { return currentUser; },
        isTrader: function() {
            if(!currentUser) return false;
            for(var i in currentUser.scopes) {
                if(currentUser.scopes[i].description == 'traderRole') {
                    return true;
                }
            }
            return false;
        },
        isAdmin: function() {
            if(!currentUser) return false;
            for(var i in currentUser.scopes) {
                if(currentUser.scopes[i].description == 'adminRole') {
                    return true;
                }
            }
            return false;
        }
    };
}]);


angular.module('TrackerApp.services').factory('BearerAuthInterceptor', ['$window', '$q', '$injector', function ($window, $q, $injector, Api) {
    return {
        request: function(config) {
            config.headers = config.headers || {};
            if ($window.localStorage.getItem('token')) {
                // may also use sessionStorage
                config.headers.Authorization = 'Bearer ' + $window.localStorage.getItem('token');
            }
            return config || $q.when(config);
        },
        response: function(response) {
            if (response.status === 401) {
                $injector.get('$state').go('login');
            }
            return response || $q.when(response);
        },
        responseError: function(response) {
            if (response.status === 401) {
                if ($window.localStorage.getItem('refresh_token') && typeof $window.localStorage.getItem('refresh_token') !== 'undefined' && $window.localStorage.getItem('refresh_token') !== 'undefined') {

                    var deferred = $q.defer();
                    Api = $injector.get('Api');
                    var user = new Api.User();
                    user.refresh_token = $window.localStorage.getItem('refresh_token');
                    user.client_id = 1;
                    user.client_secret = 1;
                    user.grant_type = 'refresh_token';
                    user.$authenticate(function() {
                    }).then(function(e) {
                        $window.localStorage.setItem('token', e.access_token);
                        $window.localStorage.setItem('refresh_token', e.refresh_token);
                        retryHttpRequest(response.config, deferred, $injector);
                    }, function(e) {

                        $window.localStorage.removeItem('token');
                        $window.localStorage.removeItem('refresh_token');
                        $window.localStorage.removeItem('token_expires');
                    });
                    return deferred.promise;
                } else {
                    $injector.get('$state').go('login');
                    return $q.reject(response);
                }

            }
            return response || $q.when(response);
        }
    };
}]);

// Register the previously created AuthInterceptor.
angular.module('TrackerApp.services').config(function ($httpProvider) {
    $httpProvider.interceptors.push('BearerAuthInterceptor');
});

function retryHttpRequest(config, deferred, $injector){
    function successCallback(response){
        deferred.resolve(response);
    }
    function errorCallback(response){
        deferred.reject(response);
    }
    var $http = $injector.get('$http');
    $http(config).then(successCallback, errorCallback);
}