exports.user = {
    permissions: current_user.permissions.map(permission => permission.name),

    can: function(permissionToCheck = null) {
        return _.includes(this.permissions, permissionToCheck);
    }
};

exports.trans = (shortString) => {
    return _.get(window.application_config.translations, shortString, shortString);
};