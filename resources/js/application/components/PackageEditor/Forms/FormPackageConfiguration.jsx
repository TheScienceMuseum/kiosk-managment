import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {parseInt} from 'lodash';
import {FormGroup, FormText, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Select from "./Elements/Select";
import {has} from 'lodash';

export default class FormPackageConfiguration extends Component {
    static propTypes = {
        packageId: PropTypes.string.isRequired,
        packageVersionId: PropTypes.string.isRequired,
        handlePackageDataChange: PropTypes.func.isRequired,
        data: PropTypes.shape({
            data: PropTypes.shape({
                image: PropTypes.oneOfType([
                    PropTypes.string,
                    PropTypes.shape({
                        imageLandscape: PropTypes.string,
                        imagePortrait: PropTypes.string,
                        imageSource: PropTypes.string,
                        imageThumbnail: PropTypes.string,
                        nameText: PropTypes.string,
                        sourceText: PropTypes.string,
                    }),
                ]),
                attractorImage: PropTypes.oneOfType([
                    PropTypes.string,
                    PropTypes.shape({
                        imageLandscape: PropTypes.string,
                        imagePortrait: PropTypes.string,
                        imageSource: PropTypes.string,
                        imageThumbnail: PropTypes.string,
                        nameText: PropTypes.string,
                        sourceText: PropTypes.string,
                    }),
                ]),
                title: PropTypes.string.isRequired,
                type: PropTypes.oneOf(["text"]).isRequired,
            }).isRequired,
        }).isRequired,
        validationErrors: PropTypes.shape({}),
    };

    constructor(props) {
        super(props);

        this.state = {
            sites: [],
        };

        this.getSites = this.getSites.bind(this);
        this.handleBSFormChange = this.handleBSFormChange.bind(this);
        this.handleFormChange   = this.handleFormChange.bind(this);
    }

    componentDidMount() {
        this.getSites();
    }

    getSites() {
        axios.get('/api/site')
            .then(response => response.data)
            .then(data => {
                const sites = data.data.filter(site => site.galleries.length)
                    .map(site => {
                        return {
                            label: site.name,
                            options: site.galleries.map(gallery => ({
                                label: gallery.name,
                                value: gallery.id,
                            })),
                        };
                    });

                this.setState(prevState => ({
                    ...prevState,
                    sites
                }));
            });
    }

    handleFormChange(field, value) {
        this.props.handlePackageDataChange(field, value);
    }

    handleBSFormChange(event) {
        const field = event.target.name;
        const value = event.target.value;
        const type = event.target.type;

        let updateValue = value;

        if (type === 'number') {
            updateValue = parseInt(value);
        }

        this.props.handlePackageDataChange(field, updateValue);
    }

    render() {
        const {validationErrors} = this.props;

        return (
            <div>
                <FormGroup>
                    <Label size={'lg'}>Package &amp; Attractor Setup</Label>
                </FormGroup>

                <FormGroup>
                    <Label>Kiosk Title</Label>
                    <Input bsSize={'sm'}
                           name={'content.titles.title'}
                           value={this.props.data.data.title}
                           onChange={this.handleBSFormChange}
                           maxLength={72}
                           invalid={has(validationErrors, 'content.titles.title')}
                    />
                    <FormText color="muted">
                            Note: the Kiosk title is limited to 45 characters
                    </FormText>
                </FormGroup>

                {!!this.state.sites.length &&
                <FormGroup>
                    <Label>Gallery</Label>
                    <Select defaultValue={ this.props.data.data.gallery? this.props.data.data.gallery : 1 }
                            field={'content.titles.gallery'}
                            handleFieldChange={this.handleFormChange}
                            value={this.props.data.data.gallery}
                            options={this.state.sites}
                    />
                </FormGroup>
                }
                <FormGroup>
                    <Label>Attractor Screen Display Timeout</Label>
                    <Input bsSize={'sm'}
                           name={'content.titles.idleTimeout'}
                           type={'number'}
                           value={this.props.data.data.idleTimeout}
                           onChange={this.handleBSFormChange}
                           invalid={has(validationErrors, 'content.titles.idleTimeout')}
                    />
                </FormGroup>

                <FormGroup>
                    <Label>
                        Attractor
                        <FormText color="muted">
                            Note: Any audio used in the attractor will be muted when played on the kiosk.
                        </FormText>
                    </Label>
                    <Asset name={'content.titles.attractor'}
                           value={this.props.data.data.attractor}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'attractor'}
                           aspectRatio={this.props.aspectRatio}
                           invalid={has(validationErrors, 'content.titles.attractor')}
                    />
                </FormGroup>
            </div>
        );
    }
}
