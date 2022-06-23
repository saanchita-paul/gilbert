import ApplicationCafFileAPI from "@scripts/api/crm/ApplicationCafFileAPI";

export default {
    getApplicationCafFileData: (sort_search_meta, params) => ApplicationCafFileAPI.getApplicationCafFileData(sort_search_meta, params),
    updateApplicationCafFileData: (cafId, cafDetail) => ApplicationCafFileAPI.updateApplicationCafFileData(cafId, cafDetail),
}
