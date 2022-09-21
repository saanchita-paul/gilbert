import ApplicationCafFileAPI from "@scripts/api/crm/ApplicationCafFileAPI";

export default {
    getApplicationCafFileData: (sort_search_meta, params) => ApplicationCafFileAPI.getApplicationCafFileData(sort_search_meta, params),
    updateApplicationCafFileData: (cafId, cafDetail) => ApplicationCafFileAPI.updateApplicationCafFileData(cafId, cafDetail),
    isPossibleToCreateCaf(serviceType, services) {
        let filteredService = services.find((svc)=> {
            return svc?.service_type === serviceType
        })
        return filteredService?.enable_caf_file;
    },
    generateCafFile: (data) => ApplicationCafFileAPI.generateCafFile(data)
}
