import React, {Component} from 'react';
import PropTypes from 'prop-types';
import ResourceListHeaderSelect from "./ResourceListHeaderSelect";
import ResourceListHeaderText from "./ResourceListHeaderText";

class ResourceListHeader extends Component {
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
                        <th className={'text-right align-middle sr-only'}>Actions</th>
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
