import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import UserIndex from './User/UserIndex';
import {BrowserRouter, Route} from 'react-router-dom';

export default class App extends Component {
    render() {
        return (
            <div className="App">
                <Route exact path='/admin/users' component={UserIndex} />
            </div>

        );
    }
}

if (document.getElementById('root')) {
    ReactDOM.render(<BrowserRouter><App /></BrowserRouter>, document.getElementById('root'));
}
