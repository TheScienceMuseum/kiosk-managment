import React, {Component} from 'react';
import {Card, CardBody, CardFooter, CardHeader, CardTitle, Table} from "reactstrap";
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {Link} from "react-router-dom";
import {BounceLoader} from "react-spinners";

export default class PackagesPendingApproval extends Component {
    constructor(props) {
        super(props);

        this.state = {packages: [], loading: true};

        this.getPackagesPendingApproval = this.getPackagesPendingApproval.bind(this);
    }

    componentDidMount() {
        this.getPackagesPendingApproval();
    }

    getPackagesPendingApproval() {
        axios.get('/api/package/versions', {
            params: {
                'filter[status]': 'pending',
                'filter[progress]': '100',
            }
        }).then(response => {
            this.setState(prevState => ({
                ...prevState,
                loading: false,
                packages: response.data.data,
            }))
        })
    }

    render() {
        return (
            <Card>
                <CardHeader className={'text-dark'}>
                    Packages Pending Approval
                </CardHeader>
                {!this.state.loading && ((this.state.packages.length &&
                    <Table size={'sm'} className={'mb-0'} borderless>
                        <tbody>
                        {this.state.packages.map(packageVersion =>
                            <tr key={`pending-package-versions-${packageVersion.id}`}>
                                <td>{packageVersion.package.name} version {packageVersion.version}</td>
                                <td className={'text-right'}>
                                    <Link className={'btn btn-xs btn-secondary'}
                                          to={`/admin/packages/${packageVersion.package.id}#versions-${packageVersion.id}`}
                                    >
                                        View
                                    </Link>
                                </td>
                            </tr>
                        )}
                        </tbody>
                    </Table>
                ) || (
                    <div className={'text-center p-3'}>
                        <strong>No packages currently pending approval</strong>
                    </div>
                ))}
                {this.state.loading &&
                <div className={'d-flex justify-content-center p-3'}>
                    <BounceLoader/>
                </div>
                }
                <CardFooter className={'text-small'}>
                    <Link to={'/admin/packages'} className={'d-flex justify-content-between'}>
                        <span className={'my-auto'}>View all packages</span>
                        <FontAwesomeIcon icon={['fal', 'angle-double-right']} className={'my-auto'}/>
                    </Link>
                </CardFooter>
            </Card>
        );
    }
}
