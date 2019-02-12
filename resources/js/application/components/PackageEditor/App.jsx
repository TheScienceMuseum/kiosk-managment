import React, {Component} from 'react';
import PropTypes from 'prop-types';

import Api from "../../../helpers/Api";
import {Alert, Button, Card, CardBody, CardFooter, CardHeader, Col, Container, FormGroup, Label, Row} from "reactstrap";
import {get, set} from 'lodash';
import FormMain from './Forms/FormMain';
import FormTitlePage from './Forms/FormTitlePage';
import FormPage from './Forms/FormPage';
import FormSection from './Forms/FormSection';
import Tree from "./Tree";

class App extends Component {
    constructor(props) {
        super(props);

        this._api = new Api('package_version');

        this.state = {
            currentlyViewingPage: null,
            currentStateFlushed: true,
            packageVersionData: null,
            packageVersionStatus: null,
        };

        this.getPackageVersionData   = this.getPackageVersionData.bind(this);
        this.flushPackageVersionData = this.flushPackageVersionData.bind(this);
        this.handlePackageDataChange = this.handlePackageDataChange.bind(this);
        this.handleAddElement        = this.handleAddElement.bind(this);
        this.handleViewElement       = this.handleViewElement.bind(this);
        this.setPackageDataState     = this.setPackageDataState.bind(this);
    }

    componentDidMount() {
        this.getPackageVersionData();
    }

    flushPackageVersionData() {
        axios.put(
            `/api/package/${this.props.packageId}/version/${this.props.packageVersionId}`,
            { package_data: this.state.packageVersionData }
        ).then(response => {
            this.setPackageDataState(response.data);

            toastr.success('Updated package data successfully.')
        });
    }

    getPackageVersionData() {
        this._api.request(
            'show',
            {},
            { id: this.props.packageVersionId, package: { id: this.props.packageId } }
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
        console.log(`path: ${resolvedPath}, current value: ${currentValue}`);
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
            console.log('handleAddElement', arguments);

            this.setState(prevState => ({
                ...prevState,
                showElementAddModalType: type,
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

                return {...prevState, packageVersionData};
            })
        }
    }

    handleViewElement(type, data, pageIndex=null, sectionIndex=null) {
        return (event) => {
            event.preventDefault();

            this.setState(prevState => ({
                ...prevState,
                currentlyViewingPage: {
                    type,
                    data,
                    pageIndex,
                    sectionIndex,
                },
            }));

            console.log('handleViewElement', arguments);
        }
    }

    render() {
        return (
            <Container fluid>
                {/*{! this.state.currentStateFlushed &&*/}
                    {/*<Row>*/}
                        {/*<Col>*/}
                            {/*<Alert color={'warning'} className={'m-0 mt-3 text-center'}>*/}
                                {/*The changes you have made will not be saved if you close or navigate away from this page.*/}
                            {/*</Alert>*/}
                        {/*</Col>*/}
                    {/*</Row>*/}
                {/*}*/}
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
                                            <FormMain data={this.state.packageVersionData}
                                                      handlePackageDataChange={this.handlePackageDataChange}
                                            />
                                            <Tree data={this.state.packageVersionData.content}
                                                  handleAddElement={this.handleAddElement}
                                                  handleViewElement={this.handleViewElement}
                                            />
                                        </CardBody>
                                        <CardFooter>
                                            <Button size={'xs'}
                                                    color={'primary'}
                                                    onClick={() => { this.props.history.push(`/admin/packages/${this.props.packageId}`) }}
                                            >Back To Package</Button>
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
                                                    <FormTitlePage data={this.state.currentlyViewingPage}
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
