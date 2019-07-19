import React from 'react';
import { contentSectionType } from '../PropTypes';
import { Button, Input, InputGroup, InputGroupAddon } from 'reactstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import CONSTANTS from '../Constants';
import PropTypes from 'prop-types';

const Leaf = (props) => {
    const {
        index,
        pageIndex,
        section,
        canMoveUp,
        canMoveDown,
        currentlyViewing,
        handleMoveElement,
        handleViewElement,
        handleRemoveElement,
    } = props;

    return (
        <InputGroup size={'sm'} className={'Leaf'}>
            <InputGroupAddon addonType={'prepend'}>
                <Button onClick={handleMoveElement('up', index, pageIndex)} disabled={!canMoveUp}>
                    <FontAwesomeIcon icon={['fas', 'arrow-alt-up']}/>
                </Button>
                <Button onClick={handleMoveElement('down', index, pageIndex)} disabled={!canMoveDown}>
                    <FontAwesomeIcon icon={['fas', 'arrow-alt-down']}/>
                </Button>
            </InputGroupAddon>

            <Input value={`${CONSTANTS.LABELS.SECTION[section.type]}: ${section.title}`} disabled/>

            <InputGroupAddon addonType={'append'}>
                <Button onClick={handleViewElement('section', section, pageIndex, index)} color={'primary'}>
                    <FontAwesomeIcon icon={['fal', 'edit']}/>
                </Button>
                <Button onClick={handleRemoveElement('section', pageIndex, index)}>
                    <FontAwesomeIcon icon={['fal', 'trash-alt']}/>
                </Button>
            </InputGroupAddon>
        </InputGroup>
    );
};

Leaf.propTypes = {
    currentViewing: PropTypes.shape({
        pageIndex: PropTypes.number,
        sectionIndex: PropTypes.number,
    }),
    section: contentSectionType,
    index: PropTypes.number.isRequired,
    pageIndex: PropTypes.number.isRequired,
    canMoveUp: PropTypes.bool.isRequired,
    canMoveDown: PropTypes.bool.isRequired,
    handleRemoveElement: PropTypes.func.isRequired,
    handleViewElement: PropTypes.func.isRequired,
    handleMoveElement: PropTypes.func.isRequired,
};

export default Leaf;
