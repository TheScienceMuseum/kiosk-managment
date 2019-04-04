import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {parseInt} from 'lodash';
import {FormGroup, FormText, Input, Label} from "reactstrap";
import Asset from "./Elements/Asset";
import Select from "./Elements/Select";

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
        return (
            <div>
                <FormGroup>
                    <Label>Kiosk Title</Label>
                        <Input bsSize={'sm'}
                               name={'content.titles.title'}
                               value={this.props.data.data.title}
                               onChange={this.handleBSFormChange}
                        />
                </FormGroup>

                <FormGroup>
                    <Label>Gallery Name</Label>
                    {!!this.state.sites.length &&
                    <Select defaultValue={1}
                            field={`gallery`}
                            handleFieldChange={this.handleFormChange}
                            options={this.state.sites}
                    />
                    }
                </FormGroup>
                <FormGroup>
                    <Label>Attractor Screen Display Timeout</Label>
                    <Input bsSize={'sm'}
                           name={'content.titles.idleTimeout'}
                           type={'number'}
                           value={this.props.data.data.idleTimeout}
                           onChange={this.handleBSFormChange}
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
                    />
                </FormGroup>
                <FormGroup className={'mb-0'}>
                    <Label>Title Image</Label>
                    <Asset name={'content.titles.image'}
                           value={this.props.data.data.image}
                           packageId={this.props.packageId}
                           packageVersionId={this.props.packageVersionId}
                           onChange={this.handleFormChange}
                           assetType={'titleImage'}
                    />
                </FormGroup>
            </div>
        );
    }
}
