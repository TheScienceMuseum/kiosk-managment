const axios = require('axios');

exports.userIndex = () => {
    return axios.get('/api/user')
        .then(({data}) => data);
};