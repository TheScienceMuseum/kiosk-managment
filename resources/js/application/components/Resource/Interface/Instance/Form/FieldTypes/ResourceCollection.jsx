import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button, ButtonGroup, Table} from "reactstrap";
import Api from "../../../../../../../helpers/Api";
import {BounceLoader} from "react-spinners";
import {each, get, has} from 'lodash';
import {ucwords} from "locutus/php/strings";

class ResourceCollection extends Component {
    constructor(props) {
        super(props);

        this._api = new Api(this.props.field.resource);

        this.state = {
            resourceIndexLoading: true,
            resourceIndex: [],
            highlightedId: null,
            pagination: {},
            filters: {},
        };

        this.resourceInstanceActions = [];

        each(this.props.field.actions, action => {
            this.resourceInstanceActions.push({
                name: action.label,
                callback: (instance) => {
                    return this._api.action(action, instance, {
                        path: (path) => this.props.history.push(path),
                        requestInstance: () => { this.requestInstance() },
                        setState: this.setState.bind(this),
                        getState: () => this.state,
                    });
                },
                display_condition: action.display_condition,
            });
        });
    }

    componentDidMount() {
        this.requestInstance();
        const [type, id] = this.props.location.hash.replace('#', '').split('-');
        if (type === this.props.field.name) {
            this.setState(prevState => ({
                ...prevState,
                highlightedId: id,
            }));
        }
    }

    requestInstance() {
        this.setState(prevState => ({
            ...prevState,
            resourceIndexLoading: true,
        }), () => {
            this._api.request('index', {}, this.props.defaultValue)
                .then(response => {
                    this.setState(prevState => ({
                        ...prevState,
                        resourceIndexLoading: false,
                        resourceIndex: response.data.data,
                        pagination: response.data.meta,
                    }));
                });
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
                            {this.resourceInstanceActions.length > 0 &&
                                <th className={'text-right'}>Actions</th>
                            }
                        </tr>
                        </thead>
                        <tbody>
                        {this.state.resourceIndexLoading ?
                            <tr>
                                <td className={'text-center'} colSpan={this._api._resourceFields.length + (this.resourceInstanceActions.length > 0 ? 1 : 0)}>
                                    <div className={'d-flex justify-content-center'}>
                                        <BounceLoader/>
                                    </div>
                                </td>
                            </tr> :
                            this.state.resourceIndex.map(row =>
                                <tr key={row.id} className={this.state.highlightedId === row.id.toString() ? 'table-primary text-light' : ''}>
                                    {this._api._resourceFields.map(field =>
                                        field.filter &&
                                        <td key={`${field.name}-${row.id}`}>
                                            {get(row, field.name)}
                                        </td>
                                    )}
                                    {this.resourceInstanceActions.length > 0 &&
                                        <td className={'text-right'}>
                                            <ButtonGroup size={'xs'}>
                                                {this.resourceInstanceActions.map(action => {
                                                    const hasDisplayCondition = has(action, 'display_condition');
                                                    const displayConditionsPassed = [];

                                                    if (hasDisplayCondition) {
                                                        let displayCondition = get(action, 'display_condition');

                                                        each(displayCondition, (value, field) => {
                                                            if (field === 'PERMISSION') {
                                                                return displayConditionsPassed.push(User.can(value));
                                                            }

                                                            if (field === 'ROLE') {
                                                                return displayConditionsPassed.push(User.can(value));
                                                            }

                                                            if (value.constructor === Boolean) {
                                                                return displayConditionsPassed.push(!!get(row, field) === value);
                                                            }

                                                            if (value.constructor === String || value.constructor === Number) {
                                                                return displayConditionsPassed.push(get(row, field) === value);
                                                            }

                                                            if (value.constructor === Array) {
                                                                return displayConditionsPassed.push(value.includes(get(row, field)));
                                                            }
                                                        });
                                                    }

                                                    if (!hasDisplayCondition || !displayConditionsPassed.includes(false)) {
                                                        return (
                                                            <Button key={`action-${row.id}-${action.name}`}
                                                                    onClick={action.callback(row)}
                                                                    color={'primary'}
                                                            >
                                                                {ucwords(action.name)}
                                                            </Button>
                                                        );
                                                    }
                                                })}
                                            </ButtonGroup>
                                        </td>
                                    }
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
