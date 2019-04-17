import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button, ButtonGroup, Table} from "reactstrap";
import Api from "../../../../../../../helpers/Api";
import DisplayCondition from "../../../../../../../helpers/DisplayCondition";
import {BounceLoader} from "react-spinners";
import {each, get, has} from 'lodash';
import {ucwords} from "locutus/php/strings";
import ResourceListPagination from '../../../List/ResourceListPagination';
import queryString from 'query-string';

class ResourceCollection extends Component {
    constructor(props) {
        super(props);

        this._api = new Api(this.props.field.resource);

        this.state = {
            resourceIndex: [],
            resourceIndexLoading: true,
            resourceIndexParams: {},
            highlightedId: null,
            pagination: {},
            filters: {},
        };

        this.resourceInstanceActions = [];

        this.handleResourceListPagination = this.handleResourceListPagination.bind(this);

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
            this._api.request('index', this.state.resourceIndexParams, this.props.defaultValue)
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

    handleResourceListPagination(page) {
        let nextPage = page;

        if (["next", "prev"].includes(page)) {
            nextPage = this.state.pagination.current_page + (page === 'next' ? 1 : -1)
        }

        return () => {
            this.setState(prevState => ({
                ...prevState,
                resourceIndexParams: {
                    ...prevState.resourceIndexParams,
                    'page[number]': nextPage,
                },
            }), () => {
                this.requestInstance();
            });
        };
    }

    render() {
        return (
            <div>
                {(this.props.field.readonly &&
                    <>
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
                                                    if (DisplayCondition.passes(action.display_condition, row)) {
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

                    <div className={'d-flex justify-content-center'}>
                    {!this.state.resourceIndexLoading &&
                        this.state.pagination.current_page &&
                        <ResourceListPagination
                            current={this.state.pagination.current_page}
                            handleResourceListPagination={this.handleResourceListPagination}
                            last={this.state.pagination.last_page}
                        />
                    }
                    </div>
                    </>
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
