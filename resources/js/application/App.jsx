import React, {Component} from 'react';
import {BrowserRouter, Redirect, Route, Switch} from "react-router-dom";
import * as Sentry from '@sentry/browser';

import {library} from '@fortawesome/fontawesome-svg-core'
import {
    faAngleDoubleRight,
    faBox,
    faDesktopAlt,
    faEye,
    faMinus,
    faPencil,
    faPlus,
    faUsers,
    faSyncAlt
} from '@fortawesome/pro-light-svg-icons';
import {faSquare} from '@fortawesome/pro-solid-svg-icons';

library.add(faBox, faDesktopAlt, faEye, faUsers, faAngleDoubleRight, faPencil, faPlus, faMinus, faSquare, faSyncAlt);

import IndexRoutes from './routes/BaseRoutes';

if (window.env !== 'local') {
    Sentry.init({
        dsn: window.sentry_dsn,
    });
}

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {error: null};
    }

    componentDidCatch(error, errorInfo) {
        if (window.env !== 'local') {
            this.setState({error});
            Sentry.withScope(scope => {
                Object.keys(errorInfo).forEach(key => {
                    scope.setExtra(key, errorInfo[key]);
                });
                Sentry.captureException(error);
            });
        }
    }

    render() {
        return (
            <BrowserRouter>
                <Switch>
                    {this.state.error &&
                        Sentry.showReportDialog()
                    }
                    {IndexRoutes.map((prop, key) =>
                        prop.redirect ?
                            <Redirect from={prop.path} to={prop.pathTo} key={`routes-redirect-${key}`}/> :
                            <Route path={prop.path} component={prop.component} key={`routes-path-${key}`}/>
                    )}
                </Switch>
            </BrowserRouter>
        );
    }
}
