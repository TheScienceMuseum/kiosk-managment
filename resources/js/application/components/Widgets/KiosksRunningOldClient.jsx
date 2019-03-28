import React, { Component } from 'react';
import {
    Card, CardBody, CardHeader, Table,
} from 'reactstrap';
import { Link } from 'react-router-dom';
import { BounceLoader } from 'react-spinners';

export default class KiosksRunningOldClient extends Component {
    constructor(props) {
        super(props);

        this.state = { kiosks: [], loading: true };

        this.getKiosksRunningOldClient = this.getKiosksRunningOldClient.bind(this);
    }

    componentDidMount() {
        this.getKiosksRunningOldClient();
    }

    getKiosksRunningOldClient() {
        axios.get('/api/kiosk', {
            params: {
                'filter[client_outdated]': true,
            },
        }).then((response) => {
            this.setState(prevState => ({
                ...prevState,
                loading: false,
                kiosks: response.data.data,
            }));
        });
    }

    render() {
        const { loading, kiosks } = this.state;

        return (
            <Card>
                <CardHeader className="text-dark">
                    Kiosks Running an Old Client
                </CardHeader>
                <CardBody className="p-0">
                    {!loading && ((kiosks.length
                        && (
                            <Table size="sm" className="mb-0" borderless>
                                <tbody>
                                    {kiosks.map(kiosk => (
                                        <tr key={`kiosks-without-packages-${kiosk.id}`}>
                                            <td>
                                                {`${kiosk.name} - ${kiosk.client_version}`}
                                            </td>
                                            <td className="text-right">
                                                <Link
                                                    className="btn btn-xs btn-secondary my-auto"
                                                    to={`/admin/kiosks/${kiosk.id}`}
                                                >
                                            View
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </Table>
                        )
                    ) || (
                        <div className="text-center p-3">
                            <strong>All kiosks are running the current client</strong>
                        </div>
                    ))}
                    {loading
                    && (
                        <div className="d-flex justify-content-center p-3">
                            <BounceLoader />
                        </div>
                    )
                    }
                </CardBody>
            </Card>
        );
    }
}
