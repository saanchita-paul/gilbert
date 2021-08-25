import UserInvitatonAPI from "@scripts/api/crm/UserInvitatonAPI";

export default {
    validateToken: (token)=> UserInvitatonAPI.validateToken(token),
    savePassword: (data, id)=> UserInvitatonAPI.savePassword(data, id),
}
