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
        return (
            <Pagination size={'sm'}>
                <PaginationItem disabled={this.props.current === 1}>
                    <PaginationLink previous onClick={this.props.handleResourceListPagination('previous')} />
                </PaginationItem>
                {this.state.pages.map(page =>
                    <PaginationItem active={this.props.current === page}
                                    key={`pagination-page-${page}`}
                    >
                        <PaginationLink onClick={this.props.handleResourceListPagination(page)}>
                            {page}
                        </PaginationLink>
                    </PaginationItem>
                )}
                <PaginationItem disabled={this.props.current === this.props.last}>
                    <PaginationLink next onClick={this.props.handleResourceListPagination('next')} />
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
