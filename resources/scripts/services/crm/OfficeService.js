import OfficeAPI from "@scripts/api/crm/OfficeAPI";

export default {
    loadOfficeData: ()=> OfficeAPI.getOfficeAllData(),
    saveOfficeData: (officeData, agencyId) => OfficeAPI.saveOfficeData(officeData, agencyId)
}
