import React, { Component } from 'react';
import PropTypes from 'prop-types';
import ResourceListHeader from './ResourceListHeader';
import { Table } from 'reactstrap';
import ResourceListRow from './ResourceListRow';
import { BounceLoader } from 'react-spinners';

const ResourceList = (props) => {
    const {
        resourceFields,
        resourceIndexList,
        resourceIndexLoading,
        resourceIndexParams,
        resourceInstanceActions,
        resourceName,
        handleResourceListPagination,
        handleResourceListParamsUpdate,
        handleResourceListSearch,
    } = props;

    return (
        <>
            <Table
                className={`ResourceList mb-0 ${resourceIndexLoading ? 'loading' : ''}`}
                hover
            >
                <ResourceListHeader
                    handleResourceListParamsUpdate={handleResourceListParamsUpdate}
                    handleResourceListSearch={handleResourceListSearch}
                    resourceFields={resourceFields}
                    resourceInstanceActions={resourceInstanceActions}
                    resourceIndexParams={resourceIndexParams}
                />
                <tbody>
                {resourceIndexList.map(resourceInstance =>
                    <ResourceListRow
                        key={`resource-instance-${resourceInstance.id}`}
                        resourceInstance={resourceInstance}
                        {...props}
                    />
                )}
                </tbody>
            </Table>
        </>
    );
};

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
