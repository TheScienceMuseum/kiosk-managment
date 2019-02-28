import React, {Component} from 'react';
import {CardGroup, Col, Row} from "reactstrap";
import PackagesPendingApproval from "../components/Widgets/PackagesPendingApproval";
import KiosksWithoutRunningPackages from "../components/Widgets/KiosksWithoutRunningPackages";
import KiosksNotRegistered from "../components/Widgets/KiosksNotRegistered";
import KiosksWithUnseenErrors from "../components/Widgets/KiosksWithUnseenErrors";
import {Link} from "react-router-dom";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

export default class Dashboard extends Component {
    render() {
        return (
            <div className={'m-3'}>
                {User.can('view all kiosks') &&
                <div>
                    <CardGroup>
                        <KiosksNotRegistered/>
                        <KiosksWithoutRunningPackages/>
                        <KiosksWithUnseenErrors/>
                    </CardGroup>
                    <Row className={'mb-3'}>
                        <Col>
                            <Link to={'/admin/kiosks'} className={'float-right'}>
                                <span className={'my-auto'}>VIEW KIOSKS</span>&nbsp;
                                <FontAwesomeIcon icon={['fal', 'angle-double-right']} className={'my-auto'}/>
                            </Link>
                        </Col>
                    </Row>
                </div>
                }
                {User.can('view all packages') &&
                <div>
                    <CardGroup>
                        <PackagesPendingApproval/>
                    </CardGroup>
                    <Row className={'mb-3'}>
                        <Col>
                            <Link to={'/admin/packages'} className={'float-right'}>
                                <span className={'my-auto'}>VIEW PACKAGES</span>&nbsp;
                                <FontAwesomeIcon icon={['fal', 'angle-double-right']} className={'my-auto'}/>
                            </Link>
                        </Col>
                    </Row>
                </div>
                }
            </div>
        );
    }
}
