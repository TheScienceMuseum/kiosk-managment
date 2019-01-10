import React, {Component} from 'react';
import PropTypes from 'prop-types';

import SidebarComponent from "../../components/Navigation/SidebarComponent";

import AdminRoutes from '../../routes/AdminRoutes';
import {Redirect, Route} from "react-router-dom";

class AdminLayout extends Component {
    render() {
        return (
            <div className={'wrapper'}>
                <SidebarComponent routes={AdminRoutes}/>
                <div className={'container-fluid'}>
                    {AdminRoutes.map((prop, key) =>
                        prop.redirect ?
                            <Redirect from={prop.path} to={prop.pathTo} key={key}/> :
                            <Route path={prop.path} component={prop.component} key={key}/>
                    )}
                </div>
            </div>
        );
    }
}

AdminLayout.propTypes = {};

export default AdminLayout;
