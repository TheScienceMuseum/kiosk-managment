import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Pagination, PaginationItem, PaginationLink } from 'reactstrap';

class ResourceListPagination extends Component {
    constructor(props) {
        super(props);

        this.state = {
            pages: this.calculateTotalPages(props.current, props.last),
        };
    }

    componentWillReceiveProps(nextProps, nextContext) {
        this.setState(prevState => ({
            ...prevState,
            pages: this.calculateTotalPages(nextProps.current, nextProps.last),
        }));
    }

    calculateTotalPages(current, total) {
        const delta = 2;

        const separate = (a, b) => [a, ...({
            0: [],
            1: [b],
            2: [a + 1, b],
        }[b - a] || ['...', b])];

        return Array(delta * 2 + 1)
            .fill()
            .map((_, index) => current - delta + index)
            .filter(page => 0 < page && page <= total)
            .flatMap((page, index, { length }) => {
                if (!index) return separate(1, page);
                if (index === length - 1) return separate(page, total);

                return [page]
            })
    }

    render() {
        const { current, handleResourceListPagination, last } = this.props;

        return (
            <Pagination
                size={'sm'}
            >
                <PaginationItem
                    disabled={current === 1}
                >
                    <PaginationLink
                        onClick={handleResourceListPagination('previous')}
                        previous
                    />
                </PaginationItem>
                {this.state.pages.map((page, index) =>
                    <PaginationItem
                        active={current === page}
                        key={`pagination-page-${index}`}
                    >
                        {(typeof page === 'number' &&
                        <PaginationLink
                            onClick={handleResourceListPagination(page)}
                        >
                            {page}
                        </PaginationLink>
                        ) || (
                        <PaginationLink
                            className={'disabled'}
                        >
                            {page}
                        </PaginationLink>
                        )}
                    </PaginationItem>
                )}
                <PaginationItem
                    disabled={current === last}
                >
                    <PaginationLink
                        onClick={handleResourceListPagination('next')}
                        next
                    />
                </PaginationItem>
            </Pagination>
        );
    }
}

ResourceListPagination.propTypes = {
    current: PropTypes.number.isRequired,
    last: PropTypes.number.isRequired,
    handleResourceListPagination: PropTypes.func.isRequired,
};

export default ResourceListPagination;
