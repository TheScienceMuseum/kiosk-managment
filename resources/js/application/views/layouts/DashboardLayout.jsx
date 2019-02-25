import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {CardColumns} from "reactstrap";
import KioskAuditLog from "../../components/Widgets/Kiosks/AuditLog";
import UserAuditLog from "../../components/Widgets/Users/AuditLog";
import PackageAuditLog from "../../components/Widgets/Packages/AuditLog";
import PackagesPendingApproval from "../../components/Widgets/Packages/PackagesPendingApproval";

class DashboardLayout extends Component {
    render() {
        return (
            <div className={'m-5'}>
                <CardColumns>
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
