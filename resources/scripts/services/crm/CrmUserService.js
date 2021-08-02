import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";
import CrmUserAPI from "@scripts/api/crm/CrmUserAPI";

export default {
    loadUserData: CrmUserAPI.getUsersData(),
}
