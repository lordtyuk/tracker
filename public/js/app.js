angular.module('TrackerApp', ['ui.router', 'ngNotificationsBar', 'ngMaterial', 'ngResource', 'TrackerApp.controllers', 'TrackerApp.services', 'ngFileUpload']);

angular.module('TrackerApp').config(function($stateProvider) {
    $stateProvider.state('products', { // state for showing all Products
        url: '/products',
        templateUrl: 'partials/products.html',
        controller: 'ProductListController'
    }).state('myProducts', {
        url: '/myproducts',
        templateUrl: 'partials/myproducts.html',
        controller: 'MyProductsController'
    }).state('viewProduct', {
        url: '/products/:id/view',
        templateUrl: 'partials/product-view.html',
        controller: 'ProductViewController'
    }).state('newProduct', {
        url: '/products/new',
        templateUrl: 'partials/product-add.html',
        controller: 'ProductCreateController'
    }).state('editProduct', {
        url: '/products/:id/edit',
        templateUrl: 'partials/product-edit.html',
        controller: 'ProductEditController'
    }).state('login', {
        url: '/login',
        templateUrl: 'partials/login.html',
        controller: 'LoginController'
    }).state('logout', {
        url: '/logout',
        controller: 'LogoutController'
    }).state('registration', {
        url: '/registration',
        templateUrl: 'partials/registration.html',
        controller: 'RegistrationController'
    }).state('users', {
        url: '/users',
        templateUrl: 'partials/users.html',
        controller: 'UserListController'
    }).state('buyers', {
        url: '/buyers',
        templateUrl: 'partials/buyers.html',
        controller: 'BuyersController'
    }).state('editUser', {
        url: '/users/:id/edit',
        templateUrl: 'partials/user-edit.html',
        controller: 'UserEditController'
    }).state('profile', {
        url: '/profile',
        templateUrl: 'partials/user-edit.html',
        controller: 'ProfileController'
    }).state('viewUser', {
        url: '/users/:id/view',
        templateUrl: 'partials/user-view.html',
        controller: 'UserViewController'
    }).state('newTrader', {
        url: '/users/new',
        templateUrl: 'partials/user-add.html',
        controller: 'UserCreateController'
    });
}).run(function($state, AuthService) {
    AuthService.wake().then(function() {
        //$state.go('products');
    }, function() {
        $state.go('login');
    })
});
