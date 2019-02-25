import React, {Component} from 'react';
import {Card, CardBody, CardFooter, CardHeader, CardTitle, Table} from "reactstrap";
import PropTypes from 'prop-types';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {Link} from "react-router-dom";
import {BounceLoader} from "react-spinners";

export default class KiosksWithoutRunningPackages extends Component {
    constructor(props) {
        super(props);

        this.state = {kiosks: [], loading: true};

        this.getPackagesPendingApproval = this.getPackagesPendingApproval.bind(this);
    }

    componentDidMount() {
        this.getPackagesPendingApproval();
    }

    getPackagesPendingApproval() {
        axios.get('/api/kiosk', {
            params: {
                'filter[current_package]': '',
                'filter[registered]': true,
            }
        }).then(response => {
            this.setState(prevState => ({
                ...prevState,
                loading: false,
                kiosks: response.data.data,
            }))
        })
    }

    render() {
        return (
            <Card>
                <CardHeader className={'text-dark'}>
                    Kiosks Without Running Package
                </CardHeader>
                {!this.state.loading && ((this.state.kiosks.length &&
                    <Table size={'sm'} className={'mb-0'} borderless>
                        <tbody>
                        {this.state.kiosks.map(kiosk =>
                            <tr key={`kiosks-without-packages-${kiosk.id}`}>
                                <td>
                                    {kiosk.name}
                                </td>
                                <td className={'text-right'}>
                                    <Link className={'btn btn-xs btn-secondary my-auto'}
                                          to={`/admin/kiosks/${kiosk.id}`}
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
                        <strong>No kiosks currently pending approval</strong>
                    </div>
                ))}
                {this.state.loading &&
                <div className={'d-flex justify-content-center p-3'}>
                    <BounceLoader/>
                </div>
                }
                <CardFooter className={'text-small'}>
                    <Link to={'/admin/kiosks'} className={'d-flex justify-content-between'}>
                        <span className={'my-auto'}>View all kiosks</span>
                        <FontAwesomeIcon icon={['fal', 'angle-double-right']} className={'my-auto'}/>
                    </Link>
                </CardFooter>
            </Card>
        );
    }
}
