import React, {Component} from 'react';
import api from '../../api';

class UserIndex extends Component {
    constructor() {
        super();
        this.state = {
            users: []
        }
    };

    componentDidMount() {
       api.userIndex()
           .then(({data}) => this.setState({users: data}))
    }


    render() {
        return (
            <div>
                test
            </div>
        );
    }
}

export default UserIndex;