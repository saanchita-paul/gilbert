import InternetProviders from "@scripts/modules/internet/constants/InternetProviders";

const getProviderAndPlan = () => {
    try {
        return InternetProviders.map(provider => provider) || [];
    } catch (error) {
        console.log("Error", error);
    }
}

export default {
    getProviderAndPlan
};
