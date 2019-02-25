import React, {Component} from 'react';
import {CardColumns} from "reactstrap";
import PackagesPendingApproval from "../../components/Widgets/PackagesPendingApproval";
import KiosksWithoutRunningPackages from "../../components/Widgets/KiosksWithoutRunningPackages";
import KiosksNotRegistered from "../../components/Widgets/KiosksNotRegistered";

class DashboardLayout extends Component {
    render() {
        return (
            <div className={'m-5'}>
                <CardColumns>
                    {User.can('view all kiosks') &&
                        <KiosksNotRegistered />
                    }
                    {User.can('view all kiosks') &&
                        <KiosksWithoutRunningPackages />
                    }
                    {User.can('publish all packages') &&
                        <PackagesPendingApproval />
                    }
                </CardColumns>
            </div>
        );
    }
}

DashboardLayout.propTypes = {};

export default DashboardLayout;
