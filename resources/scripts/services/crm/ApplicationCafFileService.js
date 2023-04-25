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
    getGilbertApplicationData: (sort_search_meta, params) =>ApplicationCafFileAPI.getGilbertApplicationData(sort_search_meta, params),
    getGilbertNBNApplicationData: (sort_search_meta, params) =>  ApplicationCafFileAPI.getNBNApplicationCafFileData(sort_search_meta, params),
    generateGilbertCafFIle: (data) => ApplicationCafFileAPI.generateGilbertCafFIle(data),
    generateNBNCafFIle: (data) => ApplicationCafFileAPI.generateNBNCafFIle(data),
    getChatbotApplication : async (sort_search_meta, params) => {
        const data = await ApplicationCafFileAPI.getApplicationCafFileData(sort_search_meta, params);
        // Store.commit('setchatbotApplications', data.data);
        return data;
    },

}
