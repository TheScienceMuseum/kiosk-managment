import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import UserIndex from './User/UserIndex';
import UserShow from './User/UserShow';
import {BrowserRouter, Route} from 'react-router-dom';
import NavigationBar from "./NavigationBar";
import KioskIndex from "./Kiosk/KioskIndex";

export default class App extends Component {
    render() {
        return (
            <div className="App">
                <NavigationBar />
                <Route exact path='/' />
                <Route exact path='/admin/users' component={UserIndex} />
                <Route path='/admin/users/:user_id' render={({match}) => <UserShow match={match} />} />
                <Route exact path='/admin/kiosks' component={KioskIndex} />
            </div>

        );
    }
}

if (document.getElementById('root')) {
    ReactDOM.render(<BrowserRouter><App /></BrowserRouter>, document.getElementById('root'));
}
