import OfficeAPI from "@scripts/api/crm/OfficeAPI";

export default {
    loadOfficeData: (meta, agencyId)=> OfficeAPI.getOfficeAllData(meta, agencyId),
    saveOfficeData: (officeData, agencyId) => OfficeAPI.saveOfficeData(officeData, agencyId),
    updateOffice: (data, id) => OfficeAPI.updateOffice(data, id),
    loadOfficeById: (id) => OfficeAPI.getOfficeData(id)
}
