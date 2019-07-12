import React, {Component} from 'react';
import PropTypes from 'prop-types';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { each } from 'lodash';
import { series } from 'async';
import { Button } from 'reactstrap';
import ResourceListHeaderSelect from "./ResourceListHeaderSelect";
import ResourceListHeaderText from "./ResourceListHeaderText";

class ResourceListHeader extends Component {
    constructor(props) {
        super(props);

        this.handleResetFilters = this.handleResetFilters.bind(this);
    }

    handleResetFilters() {
        const calls = [];

        each(this.props.resourceFields.filter(field => field.filter), (field) => {
            calls.push(callback => {
                console.log('clearing', field.name);
                if (field.filter) {
                    this.props.handleResourceListParamsUpdate(
                        field,
                        '',
                        () => {
                            callback();
                        },
                    );
                }
            });
        });

        calls.push((callback) => {
            console.log('running search');
            this.props.handleResourceListSearch();
            callback();
        });

        series(calls);
    }

    render() {
        return (
            <thead>
                <tr>
                    {_.filter(this.props.resourceFields, options => options.filter).map( options => {
                        switch (options.type) {
                            case 'text':
                                return (
                                    <ResourceListHeaderText handleResourceListParamsUpdate={this.props.handleResourceListParamsUpdate}
                                                            handleResourceListSearch={this.props.handleResourceListSearch}
                                                            key={`header-list-${options.name}`}
                                                            options={options}
                                                            initialValue={this.props.resourceIndexParams[`filter[${options.name}]`]}
                                    />
                                );

                            case 'select':
                                return (
                                    <ResourceListHeaderSelect handleResourceListParamsUpdate={this.props.handleResourceListParamsUpdate}
                                                              handleResourceListSearch={this.props.handleResourceListSearch}
                                                              key={`header-list-${options.name}`}
                                                              options={options}
                                                              initialValue={this.props.resourceIndexParams[`filter[${options.name}]`]}
                                    />
                                );
                            case 'time_ago':
                                return (
                                    <ResourceListHeaderText handleResourceListParamsUpdate={this.props.handleResourceListParamsUpdate}
                                                            handleResourceListSearch={this.props.handleResourceListSearch}
                                                            key={`header-list-${options.name}`}
                                                            options={options}
                                                            initialValue={this.props.resourceIndexParams[`filter[${options.name}]`]}
                                    />
                                );
                        }
                    })}
                    {this.props.resourceInstanceActions.length > 0 &&
                    <th className={'text-right align-middle'}>
                        <Button size={'sm'} onClick={this.handleResetFilters}>
                            <FontAwesomeIcon icon={['fas', 'times']} /> Clear Filters
                        </Button>
                    </th>
                    }
                </tr>
            </thead>
        );
    }
}

ResourceListHeader.propTypes = {
    handleResourceListParamsUpdate: PropTypes.func.isRequired,
    handleResourceListSearch: PropTypes.func.isRequired,
    resourceFields: PropTypes.array.isRequired,
    resourceIndexParams: PropTypes.object.isRequired,
    resourceInstanceActions: PropTypes.array.isRequired,
};

export default ResourceListHeader;
