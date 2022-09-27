import PowerShopSameDayConnectionAPI from "@scripts/modules/powershop/api/PowerShopSameDayConnectionAPI";

export default {
    validateSameDayConnection: (applicationId, submitType) => PowerShopSameDayConnectionAPI.validateSameDayConnection(applicationId, submitType)
}
