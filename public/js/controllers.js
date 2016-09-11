angular.module('TrackerApp.controllers', [])
    .controller('ProductListController', function($scope, $filter, $state, popupService, $window, Api, PagerService, Upload, notifications) {

        $scope.pager = {};
        $scope.products = Api.Product.query({}, function() {
            $scope.setPage = setPage;
            initController();
        });

        function initController() {
            // initialize to page 1
            $scope.setPage(1);
        }

        $scope.upload = function() {
            $scope.uploadFiles($scope.files);
        };

        // for multiple files:
        $scope.uploadFiles = function (files) {

            var selectedItems = ($filter('filter')($scope.products, {selected: 'Igen'}, true));


            var selectedIds = [];
            angular.forEach(selectedItems, function(value, key) {
                selectedIds.push(value.id);
            });

            if (files && files.length) {
                Upload.upload({url: window.host+'/v1/files', data: {files: files, ids: selectedIds}}).then(function (resp) {
                    notifications.showSuccess('File(ok) sikeresen hozzáadva!');
                    angular.forEach(selectedItems, function(value, key) {
                        selectedItems[key].selected = 'Nem';
                    });
                    $scope.files = [];
                }, function (resp) {
                    notifications.showError('File(ok) hozzáadása közben hiba lépett fel!');
                }, function (evt) {

                });
            }
        };

        function setPage(page) {
            if (page < 1 || page > $scope.pager.totalPages) {
                return;
            }

            // get pager object from service
            $scope.pager = PagerService.GetPager($scope.products.length, page);

            // get current page of items
            $scope.pager.items = $scope.products.slice($scope.pager.startIndex, $scope.pager.endIndex + 1);
        }

        $scope.deleteProduct = function(product) {
            if (popupService.showPopup('Biztosan törölni szeretnéd?')) {
                product.$delete(function() {
                    $state.reload();
                });
            }
        };
})
    .controller('ProductViewController', function($scope, $state, $stateParams, Api, AuthService) {
        $scope.product = Api.Product.get({ id: $stateParams.id });
        $scope.host = window.host;
        $scope.back = function() {
            $state.go('products');
        }
    })
    .controller('ProductCreateController', function($scope, $state, $stateParams, Api, notifications) {
        $scope.product = new Api.Product();
        $scope.resellers = Api.Reseller.query();

        $scope.addProduct = function() {
            $scope.product.$save(function() {
                notifications.showSuccess('Termék sikeresen hozzáadva!');
                $state.go('products'); //
            });
        };

        $scope.cancelProduct = function() {
            $state.go('products');
        }
})
    .controller('ProductEditController', function($scope, $state, $stateParams, Api, notifications) {
        $scope.product = Api.Product.get({ id: $stateParams.id });

        $scope.updateProduct = function() {
            $scope.product.$update(function() {
                $state.go('products');
            });
        };

        $scope.deleteFile = function(obj) {
            $scope.product.fileId = obj.id;
            $scope.product.$removeFile(function() {
                $scope.product = Api.Product.get({ id: $stateParams.id });
                notifications.showSuccess('File törölve!');
            });
        };

        $scope.cancelProduct = function() {
            $state.go('products');
        };
})
    .controller('LoginController', function($scope, $state, $stateParams, Api, AuthService) {
        $scope.user = new Api.User();
        $scope.error = false;

        $scope.login = function() {
            $scope.user.username = $scope.user.email;
            AuthService.login($scope.user).then(function() {
                //logged in
            }, function(error) {
                $scope.error = error;
            });
        }

})
    .controller('LogoutController', function($scope, $state, $stateParams, Api, AuthService) {

        AuthService.logout();
    })
    .controller('RegistrationController', function($scope, $state, $stateParams, Api) {
        $scope.user = new Api.User();

        $scope.addUser = function() {
            $scope.user.$save(function() {
                $state.go('products'); //
            });
        }
})
    .controller('UserListController', function($scope, $state, $stateParams, Api) {
         $scope.users = Api.User.query();

})
    .controller('BuyersController', function($scope, $state, $stateParams, Api) {
        $scope.users = Api.User.buyers();

})
    .controller('UserEditController', function($scope, $state, $stateParams, Api) {
        $scope.user = Api.User.get({ id: $stateParams.id });

})
    .controller('ProfileController', function($scope, $state, $stateParams, Api, AuthService) {
        $scope.user = AuthService.currentUser();

})
    .controller('UserViewController', function($scope, $state, $stateParams, Api, PagerService) {
        $scope.user = Api.User.get({ id: $stateParams.id });

        $scope.pager = {};
        $scope.products = Api.Product.userProducts({ id: $stateParams.id }, function() {
            $scope.setPage = setPage;
            initController();
        });

        function initController() {
            // initialize to page 1
            $scope.setPage(1);
        }

        function setPage(page) {
            if (page < 1 || page > $scope.pager.totalPages) {
                return;
            }

            // get pager object from service
            $scope.pager = PagerService.GetPager($scope.products.length, page);

            // get current page of items
            $scope.pager.items = $scope.products.slice($scope.pager.startIndex, $scope.pager.endIndex + 1);
        }

        $scope.back = function() {
            $state.go('users');
        }
})
    .controller('UserCreateController', function($scope, $state, $stateParams, Api, notifications) {
        $scope.user = new Api.User();

        $scope.addTrader = function() {
            $scope.user.$save(function() {
                notifications.showSuccess('Beszállító sikeresen hozzáadva!');
                $state.go('users'); //
            });
        };

        $scope.cancelTrader = function() {
            $state.go('users');
        }
})
    .controller('MyProductsController', function($scope, $state, $stateParams, Api, notifications) {
        $scope.products = Api.Product.my({ id: $stateParams.id });
        $scope.product = new Api.Product();
        $scope.showAdd = false;

        $scope.back = function() {
            $state.go('products');
        };

        $scope.toggleAdd = function() {
            $scope.showAdd = !$scope.showAdd;
        };

        $scope.addProduct = function() {
            $scope.product.$subscribe(function(e) {
                if(e.error) {
                    notifications.showError('Termék nem található!');

                } else {
                    notifications.showSuccess('Termék sikeresen hozzáadva!');
                    $state.go('myProducts');
                }

            });
        };
})
;