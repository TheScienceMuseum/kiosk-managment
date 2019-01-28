import React, {Component} from 'react';
import PropTypes from 'prop-types';

import PackageEditorApp from '../../components/PackageEditor/App';

class EditorLayout extends Component {
    render() {
        return (
            <PackageEditorApp packageId={this.props.match.params.package_id}
                              packageVersionId={this.props.match.params.package_version_id}
                              {...this.props}
            />
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
