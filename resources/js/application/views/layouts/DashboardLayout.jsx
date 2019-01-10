import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {CardColumns} from "reactstrap";
import KioskAuditLog from "../../components/Widgets/Kiosks/AuditLog";
import UserAuditLog from "../../components/Widgets/Users/AuditLog";
import PackageAuditLog from "../../components/Widgets/Packages/AuditLog";

class DashboardLayout extends Component {
    render() {
        return (
            <div className={'m-5'}>
                <CardColumns>
                    {User.can('view all kiosks') &&
                        <KioskAuditLog/>
                    }

                    {User.can('view all users') &&
                        <UserAuditLog/>
                    }

                    {User.can('view all packages') &&
                        <PackageAuditLog/>
                    }
                </CardColumns>
            </div>
        );
    }
}

DashboardLayout.propTypes = {};

export default DashboardLayout;
