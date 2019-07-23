import React, {Component} from 'react';
import FormPageMixed from "./FormPageMixed";
import FormPageTimeline from "./FormPageTimeline";
import FormPageVideo from "./FormPageVideo";

class FormPage extends Component {
    _pageTypes = {
        mixed: FormPageMixed,
        timeline: FormPageTimeline,
        video: FormPageVideo,
    };

    constructor(props) {
        super(props);

        this.getComponent   = this.getComponent.bind(this);
    }

    getComponent(type) {
        const Component = this._pageTypes[type];

        return (
            <Component {...this.props} />
        )
    }

    render() {
        return (
            <div>
                {this.getComponent(this.props.data.data.type)}
            </div>
        );
    }
}

FormPage.propTypes = {

};

export default FormPage;
