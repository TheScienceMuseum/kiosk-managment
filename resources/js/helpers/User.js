import {get, includes} from 'lodash';

class User {
    constructor() {
        this.data = window.current_user;
        this.permissions = get(this.data, 'permissions', []).map(permission => get(permission, 'name'));
        this.roles = get(this.data, 'roles', []).map(role => get(role, 'name'));
    }

    authenticated() {
        return !this.data
    }

    name() {
        return this.data ? this.data.name : null;
    }

    can(permission = null) {
        return includes(this.permissions, permission);
    }

    is(role = null) {
        return includes(this.roles, role);
    }
}

export default new User();