import InternetServiceConstant from "@scripts/modules/internet/constants/InternetServiceConstant";
import InternetAPI from "@scripts/modules/internet/api/InternetAPI";
import Store from "@scripts/store";
import InternetServiceInfoMapper from "@scripts/data/InternetServiceInfoMapper";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import {connectionServicesMapper, STATUSES_FOR_ENERGY_SUBMIT} from "@scripts/data/ConnectionApplicationMapper";

// get modem types
const getModemTypes = () => InternetAPI.getModems();
// get charity items
const getCharityItems = () => InternetServiceConstant.CHARITY;
// get provider and plan
const getProviderAndPlan = () => InternetAPI.getProviderAndPlan();

const updateInternetServiceInfo = async (data, leadId) => {
    const response = await InternetAPI.updateInternetServiceInfo(data, leadId);
    Store.commit('internetServiceInfoStore/setInternetServiceInfo', InternetServiceInfoMapper.mapData(response.internet_service_info));
    return response;
}

const updateNbnProvider = async (data, leadId) => {
    const response = await InternetAPI.updateNbnProviderInfo(data, leadId);
    await loadProviderData(leadId);
    return response;
}

const submitNBN = async (leadId) => {
    const response = await InternetAPI.NBNSubmit(leadId);
    await loadProviderData(leadId);
    return response;
}

const loadProviderData = async (leadId) => {
    const leadData = await LeadApplicationService.loadUserLead(leadId);
    setInternetProvider(leadData.internet_service_info ? leadData.internet_service_info?.connection_service?.provider_name : null);
    setInternetPlan(leadData.internet_service_info ? leadData.internet_service_info?.connection_service?.plan_type : null);
    setInternetStatus(leadData.internet_service_info ? leadData.internet_service_info?.connection_service?.status : null);
    return leadData;
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

const setIsValidForm = status => Store.commit("internetServiceInfoStore/setIsValidForm", status);

const getIsValidForm = () => Store.getters['internetServiceInfoStore/isValidForm'];

const getGoodtelPlans = async () => await InternetAPI.goodtelPlans();

const sendGoodtelPaymentLink = async (appId) => await InternetAPI.goodtelPaymentLinkSend(appId);

const STATUSES_FOR_INTERNET_SUBMIT = [
    connectionServicesMapper.STATUS_ACCEPTED,
    connectionServicesMapper.STATUS_EA_SUBMIT,
    connectionServicesMapper.STATUS_SUBMITTED,
]

const canEditService = status => {
    return STATUSES_FOR_INTERNET_SUBMIT.includes(status);
}

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
    setInternetStatus,
    updateNbnProvider,
    loadProviderData,
    submitNBN,
    getGoodtelPlans,
    sendGoodtelPaymentLink,
    setIsValidForm,
    getIsValidForm,
    canEditService
};
