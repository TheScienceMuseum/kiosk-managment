import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Button, ButtonGroup, Collapse, Input, InputGroup, InputGroupAddon} from 'reactstrap';
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import Types from "./PropTypes";

class Tree extends Component {
    _types = {
        video: 'Video',
        mixed: 'Mixed',
        title: 'Title',
        textImage: 'Image Text',
        image: 'Image',
        model: '3D Model',
    };

    constructor(props) {
        super(props);

        this.state = {
            openPages: [],
        };

        this.pageIsOpen = this.pageIsOpen.bind(this);
        this.togglePageShow = this.togglePageShow.bind(this);
    }

    pageIsOpen(pageIndex) {
        return this.state.openPages.includes(pageIndex)
    }

    togglePageShow(pageIndex) {
        return () => {
            this.setState(prevState => {
                const toggledPages = prevState.openPages;

                if (toggledPages.includes(pageIndex)) {
                    toggledPages.splice(toggledPages.indexOf(pageIndex), 1);
                } else {
                    toggledPages.push(pageIndex);
                }

                return {
                    ...prevState,
                    openPages: [...toggledPages],
                };
            });
        }
    }

    render() {
        return (
            <div>
                <div className={'font-weight-bold mb-3 d-flex justify-content-between'}>
                    <span className={'my-auto'}>Package Structure</span>
                    <ButtonGroup size={'sm'}>
                        <Button
                            color={'primary'}
                            onClick={this.props.handleViewElement('title', this.props.data.titles)}
                        >
                            Configure
                        </Button>

                        <Button
                            color={'primary'}
                            onClick={this.props.handleAddElement('page', null)}
                        >
                            Add Page
                        </Button>
                    </ButtonGroup>
                </div>
                <div>
                    <div className={'tree-list'}>
                        {this.props.data.contents.map((page, pageIndex) =>
                        <div key={`page-${pageIndex}`} className={'mb-1'}>
                            <div>
                                <div className={'page-section'}>
                                    <InputGroup size={'sm'}>
                                        {page.type !== 'video' && page.type !== 'model' &&
                                        <InputGroupAddon addonType={'prepend'}>
                                            <Button
                                                color={'primary'}
                                                onClick={this.togglePageShow(pageIndex)}
                                            >
                                                <FontAwesomeIcon fixedWidth icon={['fal', `${this.pageIsOpen(pageIndex) ? 'angle-down' : 'angle-right'}`]}/>
                                            </Button>
                                        </InputGroupAddon>
                                        }
                                        <Input value={`${this._types[page.type]}: ${page.title}`} disabled />

                                        <InputGroupAddon addonType={'append'}>
                                            {pageIndex + 1 < this.props.data.contents.length &&
                                            <Button
                                                color={'primary'}
                                                onClick={this.props.handleMoveElement('down', pageIndex)}
                                            >
                                                <FontAwesomeIcon fixedWidth icon={['fal', 'angle-double-down']}/>
                                            </Button>
                                            }
                                            {pageIndex > 0 &&
                                            <Button
                                                color={'primary'}
                                                onClick={this.props.handleMoveElement('up', pageIndex)}
                                            >
                                                <FontAwesomeIcon fixedWidth icon={['fal', 'angle-double-up']}/>
                                            </Button>
                                            }
                                            {page.type !== 'video' &&
                                            <>
                                                {page.type !== 'model' &&
                                                <Button
                                                    color={'primary'}
                                                    onClick={this.props.handleAddElement('section', pageIndex)}
                                                >
                                                    <FontAwesomeIcon fixedWidth
                                                                     icon={['fal', 'plus']}/>
                                                </Button>
                                                }
                                                <Button
                                                    color={'primary'}
                                                    onClick={this.props.handleRemoveElement('page', pageIndex)}
                                                >
                                                    <FontAwesomeIcon fixedWidth icon={['fal', 'minus']}/>
                                                </Button>
                                            </>
                                            }
                                            {page.type !== 'model' &&
                                            <Button
                                                color={'primary'}
                                                onClick={this.props.handleViewElement('page', page, pageIndex)}
                                            >
                                                <FontAwesomeIcon fixedWidth icon={['fal', 'edit']}/>
                                            </Button>
                                            }
                                        </InputGroupAddon>
                                    </InputGroup>
                                </div>
                            </div>

                            {page.type !== 'video' && page.type !== 'model' &&
                            <Collapse isOpen={this.pageIsOpen(pageIndex)} className={'sections'}>
                                {page.subpages.length === 0 &&
                                <InputGroup size={'sm'}>
                                    <Input value={'No sections, add one above'} readOnly/>
                                </InputGroup>
                                }
                                {page.subpages && page.subpages.map((section, sectionIndex) =>
                                    <div key={`page-${pageIndex}-section-${sectionIndex}`}>
                                        <InputGroup size={'sm'}>
                                            <Input value={`${this._types[section.type]}: ${section.title}`} disabled/>
                                            <InputGroupAddon addonType={'append'}>
                                                {sectionIndex + 1 < page.subpages.length &&
                                                <Button
                                                    color={'primary'}
                                                    onClick={this.props.handleMoveElement('down', sectionIndex, pageIndex)}
                                                >
                                                    <FontAwesomeIcon fixedWidth icon={['fal', 'angle-double-down']}/>
                                                </Button>
                                                }
                                                {sectionIndex > 0 &&
                                                <Button
                                                    color={'primary'}
                                                    onClick={this.props.handleMoveElement('up', sectionIndex, pageIndex)}
                                                >
                                                    <FontAwesomeIcon fixedWidth icon={['fal', 'angle-double-up']}/>
                                                </Button>
                                                }
                                                <Button
                                                    color={'primary'}
                                                    onClick={this.props.handleRemoveElement('section', pageIndex, sectionIndex)}
                                                >
                                                    <FontAwesomeIcon fixedWidth icon={['fal', 'minus']}/>
                                                </Button>
                                                <Button
                                                    color={'primary'}
                                                    onClick={this.props.handleViewElement('section', section, pageIndex, sectionIndex)}
                                                >
                                                    <FontAwesomeIcon fixedWidth icon={['fal', 'edit']}/>
                                                </Button>
                                            </InputGroupAddon>
                                        </InputGroup>
                                    </div>
                                )}
                            </Collapse>
                            }

                        </div>
                        )}
                    </div>
                </div>
            </div>
        );
    }
}

Tree.propTypes = {
    handleAddElement: PropTypes.func.isRequired,
    handleRemoveElement: PropTypes.func.isRequired,
    handleViewElement: PropTypes.func.isRequired,
    handleMoveElement: PropTypes.func.isRequired,
    data: PropTypes.shape({
        titles: PropTypes.shape({
            galleryName: PropTypes.string.isRequired,
            image: Types.asset,
            title: PropTypes.string.isRequired,
            type: PropTypes.oneOf(["text"]).isRequired,
        }).isRequired,
        contents: PropTypes.arrayOf(PropTypes.shape({
            articleID: PropTypes.string,
            subpages: PropTypes.arrayOf(PropTypes.shape({
                image: Types.image,
                pageID: PropTypes.string,
                subtitle: PropTypes.string,
                title: PropTypes.string,
                type: PropTypes.oneOf(["title", "textImage", "image", "video", "hotspot"]).isRequired,
                layout: PropTypes.oneOf(["left", "right"]),
            })),
            title: PropTypes.string,
            titleImage: Types.asset,
            type: PropTypes.oneOf(["mixed", "video", "model"]),
            videoSrc: Types.asset,
        })),
    }),
};

export default Tree;
