import React, {Component} from 'react';
import PropTypes from 'prop-types';

import Api from "../../../helpers/Api";
import {
    Alert,
    Button,
    ButtonGroup,
    Card,
    CardBody,
    Col,
    Container,
    Navbar,
    Row
} from 'reactstrap';
import { extend, get, set } from 'lodash';
import FormPackageConfiguration from './Forms/FormPackageConfiguration';
import FormPage from './Forms/FormPage';
import FormSection from './Forms/FormSection';
import Tree from "./Tree/Tree";
import AddElement from "./Forms/Elements/AddElement";
import Help from '../Navigation/Help';

class PackageEditor extends Component {
    constructor(props) {
        super(props);

        this._api = new Api('package_version');

        this.state = {
            currentlyViewingPage: null,
            currentStateFlushed: true,
            packageVersionData: null,
            packageVersionStatus: null,
            showElementAddModal: false,
            showElementAddModalType: 'page',
            showElementAddModalParent: null,
        };

        this.validPageTypes = {
            "16:9": ['mixed', 'video', 'custom'],
            "9:16": ['mixed', 'custom'],
        };

        this.validSectionTypes = {
            "16:9": ['textImage', 'video', 'image', 'textAudio'],
            "9:16": ['textImage', 'textAudio', 'textVideo'],
        };

        this.getPackageVersionData = this.getPackageVersionData.bind(this);
        this.flushPackageVersionData = this.flushPackageVersionData.bind(this);
        this.handlePackageDataChange = this.handlePackageDataChange.bind(this);
        this.handleAddElement = this.handleAddElement.bind(this);
        this.handleAddedElement = this.handleAddedElement.bind(this);
        this.handleRemoveElement = this.handleRemoveElement.bind(this);
        this.handleMoveElement = this.handleMoveElement.bind(this);
        this.handleToggleAddElementModal = this.handleToggleAddElementModal.bind(this);
        this.handleViewElement = this.handleViewElement.bind(this);
        this.setPackageDataState = this.setPackageDataState.bind(this);
        this.showPreview = this.showPreview.bind(this);
    }

    componentDidMount() {
        this.getPackageVersionData(true);
    }

    flushPackageVersionData(onFinish=null) {
        axios.put(
            `/api/package/${this.props.packageId}/version/${this.props.packageVersionId}`,
            {package_data: this.state.packageVersionData}
        ).then(response => {
            this.setPackageDataState(response.data);

            toastr.success('Updated package data successfully.')

            if (typeof onFinish === 'function') {
                onFinish();
            }
        });
    }

    getPackageVersionData(setPackageDataState = false) {
        this._api.request(
            'show',
            {},
            {id: this.props.packageVersionId, package: {id: this.props.packageId}}
        ).then(response => {
            this.setPackageDataState(response.data, setPackageDataState);
        })
    }

    setPackageDataState(responseData, openPackageConfig = false) {
        const packageVersionData = responseData.data.package_data;
        const packageVersionStatus = responseData.data.status;

        packageVersionData.name = responseData.data.package.name;
        packageVersionData.version = responseData.data.version;

        this.setState(prevState => ({
            ...prevState,
            currentStateFlushed: true,
            packageVersionData,
            packageVersionStatus,
        }));

        if (openPackageConfig) {
            this.handleViewElement('title', extend(packageVersionData.content.titles, { aspect_ratio: packageVersionData.aspect_ratio}))();
        }
    }

    handlePackageDataChange(path, value) {
        let resolvedPath = path;
        const packageVersionData = {...this.state.packageVersionData};

        if (this.state.currentlyViewingPage.sectionIndex != null) {
            resolvedPath = `subpages[${this.state.currentlyViewingPage.sectionIndex}].${resolvedPath}`;
        }

        if (this.state.currentlyViewingPage.pageIndex != null) {
            resolvedPath = `content.contents[${this.state.currentlyViewingPage.pageIndex}].${resolvedPath}`;
        }

        const currentValue = get(packageVersionData, resolvedPath);
        if (currentValue !== value) {
            set(packageVersionData, resolvedPath, value);

            this.setState(prevState => ({
                ...prevState,
                currentStateFlushed: false,
                packageVersionData: {...packageVersionData}
            }));
        }
    }

    handleAddElement(type, parent) {
        return (event) => {
            event.preventDefault();

            this.setState(prevState => ({
                ...prevState,
                showElementAddModalType: type,
                showElementAddModalParent: parent,
            }), this.handleToggleAddElementModal);
        }
    }

    handleToggleAddElementModal() {
        this.setState(prevState => ({
            ...prevState,
            showElementAddModal: !prevState.showElementAddModal,
        }));
    }

    handleAddedElement(type, setup) {
        if (type === 'page') {
            if (setup.type === 'custom') {
                const customPageId = setup['customPage'];

                axios.get(`/api/custom_page/${customPageId}`)
                    .then((response) => {
                        const { data } = response.data;

                        this.setState(prevState => {
                            let packageVersionData = prevState.packageVersionData;

                            packageVersionData.content.contents.push({
                                ...data
                            });

                            return {...prevState, packageVersionData, showElementAddModalParent: null};
                        });
                    });

            } else {
                const defaults = {
                    mixed: {
                        subpages: [],
                        title: "Mixed media page",
                        subTitle: "",
                        titleImage: null,
                        type: "mixed",
                    },
                    video: {
                        title: "A video page",
                        subTitle: "",
                        titleImage: null,
                        asset: null,
                    },
                };

                this.setState(prevState => {
                    let { packageVersionData } = prevState;

                    packageVersionData.content.contents.push({
                        ...defaults[setup.type],
                        ...setup,
                    });

                    return {...prevState, packageVersionData, showElementAddModalParent: null};
                });
            }
        }

        if (type === 'section') {
            const defaults = {
                image: {
                    content: "Image that is wide",
                    image: null,
                    layout: "left",
                    title: "title",
                    type: "image",
                },
                video: {
                    asset: null,
                    title: "A video page",
                },
                textVideo: {
                    type: "textVideo",
                    layout: "left",
                    asset: null,
                    title: "A video with text",
                    content: [
                        "Some content here"
                    ]
                },
                textImage: {
                    content: "This text will appear alongside the image",
                    image: null,
                    layout: "right",
                    title: "title",
                    type: "textImage",
                },
                textAudio: {
                    content: "This text will appear alongside the image and audio",
                    asset: null,
                    audio: null,
                    layout: "right",
                    title: "title",
                    type: "textAudio",
                },
            };

            this.setState(prevState => {
                let packageVersionData = prevState.packageVersionData;

                packageVersionData.content.contents[this.state.showElementAddModalParent].subpages.push({
                    ...defaults[setup.type],
                    ...setup,
                });

                return {...prevState, packageVersionData, showElementAddModalParent: null};
            })
        }
    }

    handleRemoveElement(type, pageIndex, sectionIndex) {
        return () => {
            if (type === 'page') {
                this.setState(prevState => {
                    const packageVersionData = prevState.packageVersionData;
                    packageVersionData.content.contents.splice(pageIndex, 1);

                    return {...prevState, packageVersionData};
                })
            }

            if (type === 'section') {
                this.setState(prevState => {
                    const packageVersionData = prevState.packageVersionData;
                    packageVersionData.content.contents[pageIndex].subpages.splice(sectionIndex, 1);

                    return {...prevState, packageVersionData};
                });
            }
        }
    }

    handleViewElement(type, data, pageIndex = null, sectionIndex = null) {
        return (event) => {
            if (event) { event.preventDefault(); }

            this.setState(prevState => {
                const newState = {...prevState};

                newState.currentlyViewingPage = {
                    type,
                    data,
                    pageIndex,
                    sectionIndex,
                };

                return newState;
            });
        }
    }

    handleMoveElement(direction, currentIndex, parentIndex=null) {
        return (event) => {
            event.preventDefault();

            if (parentIndex !== null) {
                const currentPages = [...this.state.packageVersionData.content.contents];
                const currentSections = [...this.state.packageVersionData.content.contents[parentIndex].subpages];
                const newIndex = direction === 'down' ? currentIndex + 1 : currentIndex - 1;
                const heldSections = currentSections.splice(currentIndex, 1);
                currentSections.splice(newIndex, 0, heldSections[0]);

                currentPages[parentIndex].subpages = currentSections;

                this.setState(prevState => ({
                    ...prevState,
                    packageVersionData: {
                        ...prevState.packageVersionData,
                        content: {
                            ...prevState.packageVersionData.content,
                            contents: currentPages,
                        },
                    },
                }));
            } else {
                const currentPages = [...this.state.packageVersionData.content.contents];
                const newIndex = direction === 'down' ? currentIndex + 1 : currentIndex - 1;
                const heldPages = currentPages.splice(currentIndex, 1);
                currentPages.splice(newIndex, 0, heldPages[0]);

                this.setState(prevState => ({
                    ...prevState,
                    packageVersionData: {
                        ...prevState.packageVersionData,
                        content: {
                            ...prevState.packageVersionData.content,
                            contents: [...currentPages],
                        },
                    },
                }));
            }
        }
    }

    showPreview() {
        const { packageVersionId } = this.props;
        const { packageVersionData } = this.state;

        const width = packageVersionData.aspect_ratio === '16:9' ? '1920' : '1080';
        const height = packageVersionData.aspect_ratio === '16:9' ? '1080' : '1920';

        this.flushPackageVersionData(() => {
            window.open(
                `/preview/${packageVersionId}/build`,
                "Previewing Package",
                `toolbar=no,scrollbars=no,resizable=no,width=${width},height=${height}`
            );
        });
    }

    render() {
        const { packageVersionData, currentlyViewingPage } = this.state;
        const { location } = this.props;

        return (
            <Container fluid className={'PackageEditor mb-3'}>
                {this.state.packageVersionData &&
                <>
                <AddElement
                    showModal={this.state.showElementAddModal}
                    onToggleModal={this.handleToggleAddElementModal}
                    onElementAdded={this.handleAddedElement}
                    elementType={this.state.showElementAddModalType}
                    type={this.state.showElementAddModalType}
                    validTypes={{
                        page: this.validPageTypes[packageVersionData.aspect_ratio || "16:9"],
                        section: this.validSectionTypes[packageVersionData.aspect_ratio || "16:9"],
                    }}
                />
                <Row>
                    <Col lg={{size: 12}}>
                        <Navbar className={'PackageEditorHeader d-flex justify-content-between mb-3'} dark color={'primary'}>
                            <h1 className={'text-light my-auto'}>
                                {this.state.packageVersionData.name} version {this.state.packageVersionData.version}
                            </h1>
                            <div>
                                <ButtonGroup size={'lg'}>
                                    <Help
                                        className="btn btn-secondary"
                                        location={location}
                                        showIcon={false}
                                        showText
                                    />
                                    <Button
                                        color={'secondary'}
                                        onClick={this.showPreview}
                                    >Preview</Button>
                                    <Button
                                        color={'secondary'}
                                        onClick={this.flushPackageVersionData}
                                    >Save</Button>
                                </ButtonGroup>
                            </div>
                        </Navbar>
                        <Row>
                            <Col sm={4}>
                                <Card className={'TreeContainer'}>
                                    <CardBody>
                                        <Tree data={packageVersionData}
                                              currentViewing={currentlyViewingPage}
                                              handleAddElement={this.handleAddElement}
                                              handleRemoveElement={this.handleRemoveElement}
                                              handleViewElement={this.handleViewElement}
                                              handleMoveElement={this.handleMoveElement}
                                        />
                                    </CardBody>
                                </Card>
                            </Col>
                            <Col sm={8}>
                                <Card>
                                    {/*{this.state.currentlyViewingPage !== null &&*/}
                                    {/*<CardHeader>*/}
                                    {/*    {ucwords(this.state.currentlyViewingPage.type)}*/}
                                    {/*</CardHeader>*/}
                                    {/*}*/}
                                    <CardBody className="PackageEditorForm">
                                        {(this.state.currentlyViewingPage === null &&
                                            <Alert
                                                color={'info'}
                                                className={'my-auto text-center'}
                                            >
                                                Choose a page or section on the left to edit it here.
                                            </Alert>
                                        ) || (
                                            (this.state.currentlyViewingPage.type === 'title' &&
                                                <FormPackageConfiguration
                                                    data={this.state.currentlyViewingPage}
                                                    handlePackageDataChange={this.handlePackageDataChange}
                                                    packageId={this.props.packageId}
                                                    packageVersionId={this.props.packageVersionId}
                                                    aspectRatio={this.state.packageVersionData.aspect_ratio}
                                                />
                                            ) || (this.state.currentlyViewingPage.type === 'page' &&
                                                <FormPage
                                                    data={this.state.currentlyViewingPage}
                                                    handlePackageDataChange={this.handlePackageDataChange}
                                                    packageId={this.props.packageId}
                                                    packageVersionId={this.props.packageVersionId}
                                                    aspectRatio={this.state.packageVersionData.aspect_ratio}
                                                />
                                            ) || (this.state.currentlyViewingPage.type === 'section' &&
                                                <FormSection
                                                    data={this.state.currentlyViewingPage}
                                                    handlePackageDataChange={this.handlePackageDataChange}
                                                    packageId={this.props.packageId}
                                                    packageVersionId={this.props.packageVersionId}
                                                    aspectRatio={this.state.packageVersionData.aspect_ratio}
                                                />
                                            )
                                        )}
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </Col>
                </Row>
                </>
                }
            </Container>
        );
    }
}

PackageEditor.propTypes = {
    packageId: PropTypes.oneOfType([
        PropTypes.number,
        PropTypes.string,
    ]).isRequired,
    packageVersionId: PropTypes.oneOfType([
        PropTypes.number,
        PropTypes.string,
    ]).isRequired,
};

export default PackageEditor;
