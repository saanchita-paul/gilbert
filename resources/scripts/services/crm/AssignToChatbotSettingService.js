import AssignToChatbotSettingAPI from "@scripts/api/crm/AssignToChatbotSettingAPI";

export const getData = async () => AssignToChatbotSettingAPI.get()

export const saveData = async (data) => AssignToChatbotSettingAPI.save(data)

export default {
    getData,
    saveData
}
