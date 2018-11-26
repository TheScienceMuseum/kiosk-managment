import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import UserIndex from './User/UserIndex';
import UserShow from './User/UserShow';
import {BrowserRouter, Route} from 'react-router-dom';
import NavigationBar from "./NavigationBar";
import KioskIndex from "./Kiosk/KioskIndex";
import KioskShow from "./Kiosk/KioskShow";
import Home from './Home';
import Error401 from "./Errors/Error401";

export default class App extends Component {
    render() {
        return (
            <div className="App">
                <NavigationBar />
                <Route exact path='/' component={Home} />
                <Route exact path='/admin/users' component={UserIndex} />
                <Route path='/admin/users/:user_id' component={UserShow} />
                <Route exact path='/admin/kiosks' component={KioskIndex} />
                <Route path='/admin/kiosks/:kiosk_id' render={({match}) => <KioskShow match={match}/>} />

                <Route path='/error/401' component={Error401} />
            </div>

        );
    }
}

if (document.getElementById('root')) {
    ReactDOM.render(<BrowserRouter><App /></BrowserRouter>, document.getElementById('root'));
}
