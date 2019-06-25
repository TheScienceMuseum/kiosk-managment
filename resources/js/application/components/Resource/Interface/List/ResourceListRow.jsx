import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button, ButtonGroup} from "reactstrap";
import {ucwords} from "locutus/php/strings";
import moment from "moment";
import {each, get, has} from "lodash";
import DisplayCondition from "../../../../../helpers/DisplayCondition";
import Api from "../../../../../helpers/Api";

class ResourceListRow extends Component {
    constructor(props) {
        super(props);

        this._api = new Api(props.resourceName);
        this.resourceInstanceActions = [];

        if (has(this._api._resourceActions, 'index.actions')) {
            each(this._api._resourceActions.index.actions, action => {
                if (DisplayCondition.passes(get(action, 'display_condition'), this.props.resourceInstance)) {
                    this.resourceInstanceActions.push({
                        label: action.label,
                        callback: (instance) => this._api.action(action, instance, {
                            path: (path) => this.props.history.push(path),
                            setState: this.setState.bind(this),
                            getState: () => this.state,
                        }),
                    })
                }
            });
        }

        this.resourceInstanceActions = this.resourceInstanceActions.concat(this.props.resourceInstanceActions);

        this.getInstanceValue = this.getInstanceValue.bind(this);
    }

    getInstanceValue(instance, field) {
        switch (field.type) {
            case 'text':
                let subFields = '';

                if (field.sub_fields) {
                    subFields = field.sub_fields.map(sub => instance[sub]).join(' | ');
                }

                return (
                    <span>
                        {(instance[field.name] &&
                                <strong>
                                    {instance[field.name]}
                                    {subFields &&
                                        <small className={'text-muted'}>
                                            &nbsp;({subFields})
                                        </small>
                                    }
                                </strong>
                        ) || (subFields &&
                            <strong>{subFields}</strong>
                        ) || (
                            <div className={'text-center'}>&mdash;</div>
                        )}
                    </span>
                );
            case 'select':
                if (field.multiple) {
                    return instance[field.name].map(item =>
                        <strong key={`${field.name}-${instance.id}-${item.name.replace(' ', '_')}`}
                                className={'badge badge-primary mr-1'}>
                            {field.options ?
                                field.options.find(option => option.value === item.name).label
                                : item.name
                            }
                        </strong>
                    );
                } else {
                    return (
                        <strong className={'badge badge-primary mr-1'}>
                            {field.options ?
                                field.options.find(option => option.value === instance[field.name]).label
                                : instance[field.name]
                            }
                        </strong>
                    );
                }

            case 'time_ago':
                return instance[field.name] ? moment(instance[field.name]).fromNow() : 'Never';
        }
    }

    render() {
        return (
            <tr>
                {this.props.resourceFields.filter(f => f.filter).map(field =>
                    <td key={`${field.name}-${this.props.resourceInstance.id}`}>
                        {this.getInstanceValue(this.props.resourceInstance, field)}
                    </td>
                )}

                {this.resourceInstanceActions.length > 0 &&
                    <td className={'text-right'}>
                        <ButtonGroup size={'xs'}>
                            {this.resourceInstanceActions.map(action =>
                                <Button key={`action-${this.props.resourceInstance.id}-${action.label}`}
                                        onClick={action.callback(this.props.resourceInstance)}
                                        color={'primary'}
                                >
                                    {ucwords(action.label)}
                                </Button>
                            )}
                        </ButtonGroup>
                    </td>
                }
            </tr>
        );
    }
}

ResourceListRow.propTypes = {
    resourceName: PropTypes.string.isRequired,
    resourceFields: PropTypes.array.isRequired,
    resourceInstance: PropTypes.object.isRequired,
    resourceInstanceActions: PropTypes.array,
};

export default ResourceListRow;
