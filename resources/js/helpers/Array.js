const ArrayHelpers = {
    chunk: (array, size) => {
        if (!array.length) {
            return [];
        }
        const head = array.slice(0, size);
        const tail = array.slice(size);

        return [head, ...ArrayHelpers.chunk(tail, size)];
    }
};

export default ArrayHelpers;
