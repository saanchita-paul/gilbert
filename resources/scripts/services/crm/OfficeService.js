import OfficeAPI from "@scripts/api/crm/OfficeAPI";

export default {
    loadOfficeData: ()=> OfficeAPI.getOfficeAllData(),
    saveOfficeData: (officeData) => OfficeAPI.saveOfficeData(officeData)
}
