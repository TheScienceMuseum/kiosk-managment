import React, {Component} from 'react';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";

export default class Logout extends Component {
    constructor(props) {
        super(props);
        this.logout = this.logout.bind(this);
    }

    logout() {
        axios.post('/logout')
            .catch(() => {
                window.location = window.location.hostname;
            });
    }

    render() {
        return (
            <div>
                <form>

                </form>
                <a className="nav-link"
                   onClick={this.logout}
                   style={{
                       cursor: 'pointer',
                   }}
                >
                    <FontAwesomeIcon icon={['fal', 'sign-out']} size={'2x'} fixedWidth />
                    <span className={'nav-text'}>Logout</span>
                </a>
            </div>
        );
    }
}
