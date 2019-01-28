import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button, ButtonGroup, Table} from "reactstrap";
import Api from "../../../../../../../helpers/Api";
import {BounceLoader} from "react-spinners";
import {get} from 'lodash';
import {ucwords} from "locutus/php/strings";

class ResourceCollection extends Component {
    constructor(props) {
        super(props);

        this._api = new Api(this.props.field.resource);

        this.state = {
            resourceIndexLoading: true,
            resourceIndex: [],
            pagination: {},
            filters: {},
        };

        this.resourceInstanceActions = [];

        if (this._api.hasAction('show')) {
            this.resourceInstanceActions.push({
                name: 'View',
                callback: (instance) => {
                    return () => this.props.history.push(`${props.location.pathname}/${props.field.name}/${instance.id}`);
                },
            });
        }
    }

    componentDidMount() {
        this._api.request('index', {}, this.props.defaultValue)
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
                            <th className={'text-right'}>Actions</th>
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
                                <tr key={row.id}>
                                    {this._api._resourceFields.map(field =>
                                        field.filter &&
                                        <td key={`${field.name}-${row.id}`}>
                                            {get(row, field.name)}
                                        </td>
                                    )}
                                    <td className={'text-right'}>
                                        <ButtonGroup size={'xs'}>
                                            {this.resourceInstanceActions.map(action =>
                                                <Button key={`action-${row.id}-${action.name}`}
                                                        onClick={action.callback(row)}
                                                        color={'primary'}
                                                >
                                                    {ucwords(action.name)}
                                                </Button>
                                            )}
                                        </ButtonGroup>
                                    </td>
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
