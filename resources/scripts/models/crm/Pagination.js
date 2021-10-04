export default class Pagination {
    constructor(
        {
            current_page,
            per_page,
            total,
        } = {}
    ) {
        this.current_page = current_page;
        this.per_page = per_page;
        this.total = total || 0;
    }
}
