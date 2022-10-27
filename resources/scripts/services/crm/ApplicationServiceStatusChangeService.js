import ApplicationServiceStatusChangeAPI from "@scripts/api/crm/ApplicationServiceStatusChangeAPI";

export const getAllStatus = async () => {
    let data = await ApplicationServiceStatusChangeAPI.getAllStatus();
    return data.data;
};

export const updateStatus = data => ApplicationServiceStatusChangeAPI.updateStatus(data);

export default {
    getAllStatus,
    updateStatus,
};
