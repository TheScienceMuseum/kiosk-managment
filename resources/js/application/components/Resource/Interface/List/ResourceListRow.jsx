import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button, ButtonGroup} from "reactstrap";
import {ucwords} from "locutus/php/strings";
import moment from "moment";

class ResourceListRow extends Component {
    constructor(props) {
        super(props);

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
                return instance[field.name].map(item =>
                    <strong key={`${field.name}-${instance.id}-${item.name.replace(' ', '_')}`}
                            className={'badge badge-primary mr-1'}>
                        {item.name}
                    </strong>
                );
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

                {this.props.resourceInstanceActions.length > 0 &&
                    <td className={'text-right'}>
                        <ButtonGroup size={'xs'}>
                            {this.props.resourceInstanceActions.map(action =>
                                <Button key={`action-${this.props.resourceInstance.id}-${action.name}`}
                                        onClick={action.callback(this.props.resourceInstance)}
                                        color={'primary'}
                                >
                                    {ucwords(action.name)}
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
