import Pagination from "@scripts/models/crm/Pagination";

export default {
    mapPagination: (pagination) => {
            return new Pagination({...pagination});
    }
}
