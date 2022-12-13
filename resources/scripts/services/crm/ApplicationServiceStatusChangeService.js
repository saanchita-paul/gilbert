import ApplicationServiceStatusChangeAPI from "@scripts/api/crm/ApplicationServiceStatusChangeAPI";

export const getAllStatus = async () => {
    let data = await ApplicationServiceStatusChangeAPI.getAllStatus();
    return data.data;
};

export const updateStatus = (applicationId, data) => ApplicationServiceStatusChangeAPI.updateStatus(applicationId, data);

export const updateBulkStatus = data => ApplicationServiceStatusChangeAPI.updateBulkStatus(data);

export const getAllLogs = async (applicationId) => {
    let data = await ApplicationServiceStatusChangeAPI.getAllLogs(applicationId);
    return data.data;
};

export const getServiceStatusDD = async (formData) => {
    let data = await ApplicationServiceStatusChangeAPI.getServiceStatusDD(formData);
    return data.data;
};

export const getWaterServiceStatusDD = async (formData) => {
    let data = await ApplicationServiceStatusChangeAPI.getWaterServiceStatusDD(formData);
    return data.data;
};

export const getInternetServiceStatusDD = async (formData) => {
    let data = await ApplicationServiceStatusChangeAPI.getInternetServiceStatusDD(formData);
    return data.data;
};


export default {
    getAllStatus,
    updateStatus,
    updateBulkStatus,
    getAllLogs,
    getServiceStatusDD,
    getWaterServiceStatusDD,
    getInternetServiceStatusDD
};
