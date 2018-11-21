const axios = require('axios');

exports.userIndex = () => {
    return axios.get('/api/user')
        .then(({data}) => data);
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

exports.kioskIndex = () => {
    return axios.get('/api/kiosk')
        .then(({data}) => data);
};

exports.kioskShow = (id) => {
    return axios.get(`/api/kiosk/${id}`)
        .then(({data}) => data);
}