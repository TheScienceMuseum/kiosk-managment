import React, {Component} from 'react';
import {BrowserRouter, Redirect, Route, Switch} from "react-router-dom";

import {library} from '@fortawesome/fontawesome-svg-core'
import {faBox, faDesktopAlt, faEye, faUsers, faAngleDoubleRight} from '@fortawesome/pro-light-svg-icons';
library.add(faBox, faDesktopAlt, faEye, faUsers, faAngleDoubleRight);

import IndexRoutes from './routes/BaseRoutes';

export default class App extends Component {
    render() {
        return (
            <BrowserRouter>
                <Switch>
                    {IndexRoutes.map((prop, key) =>
                        prop.redirect ?
                            <Redirect from={prop.path} to={prop.pathTo} key={key}/> :
                            <Route path={prop.path} component={prop.component} key={key}/>
                    )}
                </Switch>
            </BrowserRouter>
        );
    }
}
