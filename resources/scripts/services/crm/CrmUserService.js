import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";
import CrmUserAPI from "@scripts/api/crm/CrmUserAPI";

export default {
    loadUserData: (meta, agencyId, officeId) => CrmUserAPI.getUsersData(meta, agencyId, officeId),
    saveUser: (crmUser, officeId)=> CrmUserAPI.saveUser(crmUser, officeId),
    loadAllUser: (meta)=> CrmUserAPI.getUserAllData(meta),
}
