import InternetServiceConstant from "@scripts/modules/internet/constants/InternetServiceConstant";
import InternetAPI from "@scripts/modules/internet/api/InternetAPI";

// get modem types
const getModemTypes = () => InternetServiceConstant.MODEM_TYPES;
// get charity items
const getCharityItems = () => InternetServiceConstant.CHARITY;
// get provider and plan
const getProviderAndPlan = () => InternetAPI.getProviderAndPlan();

export default {
    getModemTypes,
    getCharityItems,
    getProviderAndPlan
};
