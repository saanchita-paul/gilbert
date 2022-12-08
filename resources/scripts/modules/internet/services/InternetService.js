import InternetServiceConstant from "@scripts/modules/internet/constants/InternetServiceConstant";
import InternetAPI from "@scripts/modules/internet/api/InternetAPI";
import Store from "@scripts/store";
import InternetServiceInfoMapper from "@scripts/data/InternetServiceInfoMapper";

// get modem types
const getModemTypes = () => InternetServiceConstant.MODEM_TYPES;
// get charity items
const getCharityItems = () => InternetServiceConstant.CHARITY;
// get provider and plan
const getProviderAndPlan = () => InternetAPI.getProviderAndPlan();

const updateInternetServiceInfo = async (data, leadId) => {
    const response = await InternetAPI.updateInternetServiceInfo(data, leadId);
    Store.commit('internetServiceInfoStore/setInternetServiceInfo', InternetServiceInfoMapper.mapData(response.internet_service_info));
    return response;
}

const loadInternetServiceInfo = () => {
    return Store.getters['internetServiceInfoStore/getInternetServiceInfo'];
}

const getInternetProvider = () => Store.getters['internetServiceInfoStore/internetProvider'];

const setInternetProvider = provider => Store.commit("internetServiceInfoStore/setInternetProvider", provider);

const getInternetPlan = () => Store.getters['internetServiceInfoStore/internetPlan'];

const setInternetPlan = provider => Store.commit("internetServiceInfoStore/setInternetPlan", provider);

const getInternetStatus = () => Store.getters['internetServiceInfoStore/internetStatus'];

const setInternetStatus = provider => Store.commit("internetServiceInfoStore/setInternetStatus", provider);

export default {
    getModemTypes,
    getCharityItems,
    getProviderAndPlan,
    updateInternetServiceInfo,
    loadInternetServiceInfo,
    getInternetProvider,
    setInternetProvider,
    getInternetPlan,
    setInternetPlan,
    getInternetStatus,
    setInternetStatus
};
