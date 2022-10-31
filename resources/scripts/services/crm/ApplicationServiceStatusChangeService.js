import ApplicationServiceStatusChangeAPI from "@scripts/api/crm/ApplicationServiceStatusChangeAPI";

export const getAllStatus = async () => {
    let data = await ApplicationServiceStatusChangeAPI.getAllStatus();
    return data.data;
};

export const updateStatus = data => ApplicationServiceStatusChangeAPI.updateStatus(data);

export const updateBulkStatus = data => ApplicationServiceStatusChangeAPI.updateBulkStatus(data);

export const getAllLogs = async (applicationId) => {
    let data = await ApplicationServiceStatusChangeAPI.getAllLogs(applicationId);
    return data.data;
};

export default {
    getAllStatus,
    updateStatus,
    updateBulkStatus,
    getAllLogs
};
