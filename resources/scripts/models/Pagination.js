export default class Pagination {
    constructor(
        {
            currentPage,
            hasMorePages,
            nextPage,
            perPage,
            previousPage,
            total
        } = {}
    ) {
        this.currentPage = currentPage || null;
        this.hasMorePages = hasMorePages || null;
        this.nextPage = nextPage || null;
        this.perPage = perPage || null;
        this.previousPage = previousPage || null;
        this.total = total || null;

    }
}
