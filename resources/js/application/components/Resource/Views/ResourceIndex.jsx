import React, {Component} from 'react';
import PropTypes from 'prop-types';
import { Alert, Button, Card, CardFooter, CardHeader, Navbar } from 'reactstrap';
import {ucwords} from "locutus/php/strings";
import queryString from 'query-string';

import ResourceList from "../Interface/List/ResourceList";
import ResourceListPagination from "../Interface/List/ResourceListPagination";
import Api from "../../../../helpers/Api";

class ResourceIndex extends Component {
    constructor(props) {
        super(props);

        const initialParams = queryString.parse(props.location.search);

        this.state = {
            resourceIndexList:          [],
            resourceIndexLoading:       true,
            resourceIndexParams:        initialParams || {},
            resourceIndexPagination:    {},
            resourceInstanceSelected:   null,
        };

        this._api = new Api(props.resourceName);

        this.handleResourceListPagination   = this.handleResourceListPagination.bind(this);
        this.handleResourceListParamsUpdate = this.handleResourceListParamsUpdate.bind(this);
        this.handleResourceListSearch       = this.handleResourceListSearch.bind(this);
        this.request                        = this.request.bind(this);

        this.resourceIndexActions       = [];
        this.resourceInstanceActions    = [];

        if (this._api.hasAction('show')) {
            this.resourceInstanceActions.push({
                label: 'View',
                callback: (instance) => {
                    return () => this.props.history.push(`/admin/${props.resourceName}s/${instance.id}`);
                },
            });
        }

        if (this._api._resourceActions.index.views) {
            _.each(this._api._resourceActions.index.views, view => {
                this.resourceIndexActions.push({
                    label: view.name,
                    callback: () => {
                        if (view.params) {
                            this.props.history.push({
                                search: `?${queryString.stringify(view.params)}`,
                            });
                        }
                    },
                })
            });
        }

        if (this._api.hasAction('store') && User.can(`create new ${this.props.resourceName}s`) ) {
            this.resourceIndexActions.push({
                label: 'Create ' + this._api._resourceName,
                callback: () => {
                    this.props.history.push(`/admin/${this.props.resourceName}s/create`);
                },
            })
        }
    }

    componentDidMount() {
        this.request();
    }

    request() {
        this.setState(prevState => ({
            ...prevState,
            resourceIndexLoading: true,
        }), () => {
            this._api.request('index', this.state.resourceIndexParams)
                .then(response => {
                    this.setState(prevState => ({
                        ...prevState,
                        resourceIndexList: response.data.data,
                        resourceIndexLoading: false,
                        resourceIndexPagination: response.data.meta
                    }));

                    const query = queryString.stringify(this.state.resourceIndexParams);

                    this.props.history.push({
                        search: `?${query}`,
                    });
                });
        });
    }

    handleResourceListPagination(page) {
        let nextPage = page;

        if (["next", "prev"].includes(page)) {
            nextPage = this.state.resourceIndexPagination.current_page + (page === 'next' ? 1 : -1)
        }

        return () => {
            this.setState(prevState => ({
                ...prevState,
                resourceIndexParams: {
                    ...prevState.resourceIndexParams,
                    'page[number]': nextPage,
                },
            }), () => {
                this.request();
            });
        };
    }

    handleResourceListParamsUpdate(field, value, callback) {
        console.log('updating params', field.name, value);

        this.setState(prevState => {
            const params = {...prevState.resourceIndexParams};

            if (! value) {
                delete params[`filter[${field.name}]`];
            } else {
                params[`filter[${field.name}]`] = value;
            }

            return {
                ...prevState,
                resourceIndexParams: params,
            }
        }, callback);
    }

    handleResourceListSearch() {
        this.setState(prevState => {
            const params = {...prevState.resourceIndexParams};

            delete params['page[number]'];

            return {
                ...prevState,
                resourceIndexParams: params,
            }
        }, () => {
            const query = queryString.stringify(this.state.resourceIndexParams);

            this.request();
        });
    }

    render() {
        return (
            <div className={'ResourceIndex'}>
                <Navbar className={'ResourceHeader d-flex justify-content-between'} dark color={'primary'}>
                    <h1 className={'text-light my-auto'}>
                        Manage {ucwords(this.props.resourceName.replace('_', ' '))}s
                    </h1>
                    <div>
                        {this.resourceIndexActions.map(action =>
                            <Button key={`index-actions-${action.label}`}
                                    className={'mx-1'}
                                    onClick={action.callback}
                                    color={action.color || 'secondary'}
                            >
                                {action.label}
                            </Button>
                        )}
                    </div>
                </Navbar>

                <ResourceList handleResourceListPagination={this.handleResourceListPagination}
                              handleResourceListParamsUpdate={this.handleResourceListParamsUpdate}
                              handleResourceListSearch={this.handleResourceListSearch}
                              resourceFields={this._api._resourceFields}
                              resourceIndexList={this.state.resourceIndexList}
                              resourceIndexLoading={this.state.resourceIndexLoading}
                              resourceIndexParams={this.state.resourceIndexParams}
                              resourceInstanceActions={this.resourceInstanceActions}
                              resourceName={this.props.resourceName}
                              {...this.props}
                />

                <Navbar className={'d-flex justify-content-center'}>
                    {this.state.resourceInstanceSelected === null &&
                        !!this.state.resourceIndexPagination.current_page &&
                        !!this.state.resourceIndexPagination.total &&
                        <ResourceListPagination current={this.state.resourceIndexPagination.current_page}
                                                last={this.state.resourceIndexPagination.last_page}
                                                handleResourceListPagination={this.handleResourceListPagination}
                        />
                    }
                </Navbar>
            </div>
        );
    }
}

ResourceIndex.propTypes = {
    params: PropTypes.object,
    resourceName: PropTypes.string.isRequired,
};

export default ResourceIndex;
