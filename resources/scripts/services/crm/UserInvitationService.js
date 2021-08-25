import UserInvitatonAPI from "@scripts/api/crm/UserInvitatonAPI";

export default {
    validateToken: (token)=> UserInvitatonAPI.validateToken(token),
}
