import ResetPasswordApi from "@scripts/api/crm/ResetPasswordApi";

export default {
    checkIsValidateToken: (data)=> ResetPasswordApi.checkIsValidToken(data),
    savePassword: (data)=> ResetPasswordApi.savePassword(data)
}
