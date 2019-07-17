import React, {Component} from 'react';
import PropTypes from 'prop-types';
import AdminRoutes from '../../routes/AdminRoutes';
import PackageEditorApp from '../../components/PackageEditor/PackageEditor';
import SidebarComponent from '../../components/Navigation/SidebarComponent';

class EditorLayout extends Component {
    render() {
        return (
            <div className={'wrapper'}>
                <SidebarComponent routes={AdminRoutes} location={this.props.location}/>
                <PackageEditorApp packageId={this.props.match.params.package_id}
                                  packageVersionId={this.props.match.params.package_version_id}
                                  {...this.props}
                />
            </div>
        );
    }
}

EditorLayout.propTypes = {
    match: PropTypes.shape({
        params: PropTypes.shape({
            package_id: PropTypes.oneOfType([
                PropTypes.number,
                PropTypes.string
            ]).isRequired,
            package_version_id: PropTypes.oneOfType([
                PropTypes.number,
                PropTypes.string
            ]).isRequired,
        }).isRequired,
    }).isRequired,
};

export default EditorLayout;
