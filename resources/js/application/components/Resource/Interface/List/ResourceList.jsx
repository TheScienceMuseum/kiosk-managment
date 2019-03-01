import React, {Component} from 'react';
import PropTypes from 'prop-types';
import ResourceListHeader from "./ResourceListHeader";
import {Table} from "reactstrap";
import ResourceListRow from "./ResourceListRow";
import {BounceLoader} from "react-spinners";

class ResourceList extends Component {
    render() {
        return (
            <Table hover responsive className={'mb-0'}>
                <ResourceListHeader handleResourceListParamsUpdate={this.props.handleResourceListParamsUpdate}
                                    handleResourceListSearch={this.props.handleResourceListSearch}
                                    resourceFields={this.props.resourceFields}
                                    resourceInstanceActions={this.props.resourceInstanceActions}
                                    resourceIndexParams={this.props.resourceIndexParams}
                />
                <tbody>
                {(this.props.resourceIndexLoading &&
                        <tr>
                            <td colSpan={this.props.resourceFields.length + 1} style={{height: '480px'}} className={'align-middle'}>
                                <div className={'d-flex justify-content-center'}>
                                    <BounceLoader/>
                                </div>
                            </td>
                        </tr>
                ) || (this.props.resourceIndexList.map(resourceInstance =>
                        <ResourceListRow key={`resource-instance-${resourceInstance.id}`}
                                         resourceName={this.props.resourceName}
                                         resourceInstance={resourceInstance}
                                         resourceFields={this.props.resourceFields}
                                         resourceInstanceActions={this.props.resourceInstanceActions}
                        />
                    )
                )}
                </tbody>
            </Table>
        );
    }
}

ResourceList.propTypes = {
    resourceName: PropTypes.string.isRequired,
    resourceFields: PropTypes.array.isRequired,
    resourceIndexList: PropTypes.array.isRequired,
    resourceIndexLoading: PropTypes.bool.isRequired,
    resourceIndexParams: PropTypes.object.isRequired,
    resourceInstanceActions: PropTypes.array,
    handleResourceListPagination: PropTypes.func.isRequired,
    handleResourceListParamsUpdate: PropTypes.func.isRequired,
    handleResourceListSearch: PropTypes.func.isRequired,
};

export default ResourceList;
