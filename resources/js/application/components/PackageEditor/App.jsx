import React, {Component} from 'react';
import PropTypes from 'prop-types';

import Api from "../../../helpers/Api";
import {Alert, Button, Card, CardBody, CardFooter, CardHeader, Col, Container, FormGroup, Label, Row} from "reactstrap";
import {get, set} from 'lodash';
import FormMain from './Forms/FormMain';
import FormPackageConfiguration from './Forms/FormPackageConfiguration';
import FormPage from './Forms/FormPage';
import FormSection from './Forms/FormSection';
import Tree from "./Tree";
import AddElement from "./Forms/Elements/AddElement";
import {Link} from "react-router-dom";

class App extends Component {
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
    }

    componentDidMount() {
        this.getPackageVersionData();
    }

    flushPackageVersionData() {
        axios.put(
            `/api/package/${this.props.packageId}/version/${this.props.packageVersionId}`,
            {package_data: this.state.packageVersionData}
        ).then(response => {
            this.setPackageDataState(response.data);

            toastr.success('Updated package data successfully.')
        });
    }

    getPackageVersionData() {
        this._api.request(
            'show',
            {},
            {id: this.props.packageVersionId, package: {id: this.props.packageId}}
        ).then(response => {
            this.setPackageDataState(response.data);
        })
    }

    setPackageDataState(responseData) {
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

        this.handleViewElement('title', packageVersionData.content.titles)();
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
        console.log(`path: ${resolvedPath}, current value: ${JSON.stringify(currentValue)}`);
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
        console.log(arguments);
        if (type === 'page') {
            const defaults = {
                mixed: {
                    subpages: [],
                    title: "Mixed media page",
                    titleImage: null,
                    type: "mixed",
                },
                video: {
                    image: null,
                    title: "A video page",
                    titleImage: null,
                    videoSrc: null,
                }
            };

            this.setState(prevState => {
                let packageVersionData = prevState.packageVersionData;

                packageVersionData.content.contents.push({
                    ...defaults[setup.type],
                    ...setup,
                });

                return {...prevState, packageVersionData, showElementAddModalParent: null};
            })
        }

        if (type === 'section') {
            const defaults = {
                title: {
                    title: "Mixed media page",
                    image: null,
                    type: "title",
                },
                image: {
                    content: "Image that is wide",
                    image: null,
                    layout: "left",
                    title: "title",
                    type: "image",
                },
                textImage: {
                    content: "This text will appear alongside the image",
                    image: null,
                    layout: "right",
                    title: "title",
                    type: "textImage",
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

            console.log('handleViewElement', arguments);
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

                console.log(currentPages, currentSections);

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

    render() {
        return (
            <Container fluid className={'mb-3'}>
                <AddElement showModal={this.state.showElementAddModal}
                            onToggleModal={this.handleToggleAddElementModal}
                            onElementAdded={this.handleAddedElement}
                            type={this.state.showElementAddModalType}
                />
                {this.state.packageVersionData &&
                <Row>
                    <Col lg={{size: 12}} className={'mt-3'}>
                        <Row>
                            <Col sm={4}>
                                <Card>
                                    <CardHeader>
                                        Package {this.state.packageVersionData.name} version {this.state.packageVersionData.version}
                                    </CardHeader>
                                    <CardBody>
                                        {/*<FormMain data={this.state.packageVersionData}*/}
                                        {/*handlePackageDataChange={this.handlePackageDataChange}*/}
                                        {/*/>*/}
                                        <Tree data={this.state.packageVersionData.content}
                                              handleAddElement={this.handleAddElement}
                                              handleRemoveElement={this.handleRemoveElement}
                                              handleViewElement={this.handleViewElement}
                                              handleMoveElement={this.handleMoveElement}
                                        />
                                    </CardBody>
                                    <CardFooter>
                                        <Link className={'btn btn-xs btn-primary'}
                                              to={`/admin/packages/${this.props.packageId}`}
                                        >Back To Package</Link>
                                        <Button size={'xs'}
                                                color={'primary'}
                                                className={'float-right'}
                                                onClick={this.flushPackageVersionData}
                                        >Save</Button>
                                    </CardFooter>
                                </Card>
                            </Col>
                            <Col sm={8}>
                                <Card>
                                    <CardBody>
                                        {(this.state.currentlyViewingPage === null &&
                                            <Alert color={'info'} className={'my-auto text-center'}>
                                                Choose a page or section on the left to edit it here.
                                            </Alert>
                                        ) || (
                                            (this.state.currentlyViewingPage.type === 'title' &&
                                                <FormPackageConfiguration data={this.state.currentlyViewingPage}
                                                                          handlePackageDataChange={this.handlePackageDataChange}
                                                                          packageId={this.props.packageId}
                                                                          packageVersionId={this.props.packageVersionId}
                                                />
                                            ) || (this.state.currentlyViewingPage.type === 'page' &&
                                                <FormPage data={this.state.currentlyViewingPage}
                                                          handlePackageDataChange={this.handlePackageDataChange}
                                                          packageId={this.props.packageId}
                                                          packageVersionId={this.props.packageVersionId}
                                                />
                                            ) || (this.state.currentlyViewingPage.type === 'section' &&
                                                <FormSection data={this.state.currentlyViewingPage}
                                                             handlePackageDataChange={this.handlePackageDataChange}
                                                             packageId={this.props.packageId}
                                                             packageVersionId={this.props.packageVersionId}
                                                />
                                            )
                                        )}
                                    </CardBody>
                                </Card>
                            </Col>
                        </Row>
                    </Col>
                    {/*<Col lg={{size: 12}} className={'mt-3'}>*/}
                    {/*<Preview data={this.state.packageVersionData}/>*/}
                    {/*</Col>*/}
                </Row>
                }
            </Container>
        );
    }
}

App.propTypes = {
    packageId: PropTypes.oneOfType([
        PropTypes.number,
        PropTypes.string,
    ]).isRequired,
    packageVersionId: PropTypes.oneOfType([
        PropTypes.number,
        PropTypes.string,
    ]).isRequired,
};

export default App;
