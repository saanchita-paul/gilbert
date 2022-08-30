import PowershopAPI from "@scripts/modules/powershop/api/PowershopAPI";

export default {
    getPowerShopData: (query) => PowershopAPI.getPowerShopData(query),
    updatePaymentInformation: (field, value, applicationId) => PowershopAPI.updatePaymentInformation(field, value, applicationId),
};
