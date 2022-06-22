import ApplicationCafFileAPI from "@scripts/api/crm/ApplicationCafFileAPI";

export default {
    getApplicationCafFileData: page => ApplicationCafFileAPI.getApplicationCafFileData(page),
    updateApplicationCafFileData: (cafId, cafDetail) => ApplicationCafFileAPI.updateApplicationCafFileData(cafId, cafDetail),
}
