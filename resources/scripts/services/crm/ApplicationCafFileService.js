import ApplicationCafFileAPI from "@scripts/api/crm/ApplicationCafFileAPI";

export default {
    getApplicationCafFileData: (sort_search_meta, params) => ApplicationCafFileAPI.getApplicationCafFileData(sort_search_meta, params),
    updateApplicationCafFileData: (cafId, cafDetail) => ApplicationCafFileAPI.updateApplicationCafFileData(cafId, cafDetail),
    rejectionReasonCafFileData: (connectionServiceID) => ApplicationCafFileAPI.rejectionReasonCafFileData(connectionServiceID),
    isPossibleToCreateCaf(serviceType, services) {
        let filteredService = services.find((svc)=> {
            return svc?.service_type === serviceType
        })
        return filteredService?.enable_caf_file;
    },
    getGilbertApplicationData: (sort_search_meta, params) => ApplicationCafFileAPI.getGilbertApplicationData(sort_search_meta, params),
    generateGilbertCafFIle: (data) => ApplicationCafFileAPI.generateGilbertCafFIle(data),
}
