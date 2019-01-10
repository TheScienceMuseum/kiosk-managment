import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Table} from "reactstrap";
import Api from "../../../../../../../helpers/Api";
import {BounceLoader} from "react-spinners";

class ResourceCollection extends Component {
    constructor(props) {
        super(props);

        this._api = new Api('kiosk_logs');

        this.state = {
            resourceIndexLoading: true,
            resourceIndex: [],
            pagination: {},
            filters: {},
        }
    }

    componentDidMount() {
        this._api.request('index', '', this.props.defaultValue)
            .then(response => {
                this.setState(prevState => ({
                    ...prevState,
                    resourceIndexLoading: false,
                    resourceIndex: response.data.data,
                    pagination: response.data.meta,
                }));
            });
    }

    render() {
        return (
            <div>
                {(this.props.field.readonly &&
                    <Table responsive hover>
                        <thead>
                            <tr>
                                {this._api._resourceFields.map(field =>
                                    field.filter && <th key={`${this.props.field.name}-rc-${field.name}`}>{field.name}</th>
                                )}
                            </tr>
                        </thead>
                        <tbody>
                        {this.state.resourceIndexLoading ?
                            <tr>
                                <td className={'text-center'} colSpan={'3'}>
                                    <div className={'d-flex justify-content-center'}>
                                        <BounceLoader/>
                                    </div>
                                </td>
                            </tr> :
                            this.state.resourceIndex.map(row =>
                                <tr key={row.timestamp}>
                                    <td>{row.level}</td>
                                    <td>{row.message}</td>
                                    <td>{row.timestamp}</td>
                                </tr>
                            )
                        }
                        </tbody>
                    </Table>
                ) || (
                    <span className={'text-danger'}>Cannot create a non-readonly resource collection field.</span>
                )}
            </div>
        );
    }
}

ResourceCollection.propTypes = {
    defaultValue: PropTypes.oneOfType([PropTypes.object]),
    handleFieldChange: PropTypes.func.isRequired,
    field: PropTypes.object.isRequired,
};

export default ResourceCollection;
