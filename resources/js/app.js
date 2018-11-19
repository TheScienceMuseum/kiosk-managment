
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

(function() {
    $('.submitsApprovalForm').on('click', function (ev) {
        var $target = $(ev.currentTarget);
        $target.parent().parent().find('form').submit();
    });
})();

window.user = {
    permissions: current_user.permissions.map(permission => permission.name),

    can: function(permissionToCheck = null) {
        return _.includes(this.permissions, permissionToCheck);
    }
};

// require('./components/Example');