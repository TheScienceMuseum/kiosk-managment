import _ from 'lodash';

class User {
    constructor() {
        this.data = window.current_user;
        this.permissions = this.data ? this.data.permissions.map(permission => permission.name) : [];
    }

    authenticated() {
        return !this.data
    }

    name() {
        return this.data ? this.data.name : null;
    }

    can(permission = null) {
        return _.includes(this.permissions, permission);
    }
}

export default new User();