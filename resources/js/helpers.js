exports.user = {
    guest: !current_user,
    name: current_user.name || null,
    permissions: current_user.permissions.map(permission => permission.name) || null,
    can: function (permissionToCheck = null) {
        return _.includes(this.permissions, permissionToCheck);
    }
};

exports.trans = (shortString) => {
    return _.get(window.application_config.translations, shortString, shortString);
};