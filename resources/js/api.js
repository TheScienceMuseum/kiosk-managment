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