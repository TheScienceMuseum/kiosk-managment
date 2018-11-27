const axios = require('axios');

exports.userIndex = (apiQueryString) => {
    return axios.get(`/api/user?${apiQueryString}`)
        .then(({data}) => {
            return data;
        });
};

exports.userRoleIndex = () => {
    return axios.get('/api/user/role')
        .then(({data}) => data);
};

exports.userLogout = () => {
    return axios.post('/logout');
};

exports.userShow = (id) => {
    return axios.get(`/api/user/${id}`)
        .then(({data}) => data);
};

exports.userCreate = (newUser) => {
    return axios.post('/api/user', {
        name: newUser.name,
        email: newUser.email,
        roles: newUser.roles,
        send_invite: false,
    }).then(({data}) => data);
};

exports.kioskIndex = () => {
    return axios.get('/api/kiosk')
        .then(({data}) => data);
};

exports.kioskShow = (id) => {
    return axios.get(`/api/kiosk/${id}`)
        .then(({data}) => data);
};