import IdDetail from "@scripts/models/chatbot/IdDetail";
import PersonalDetail from "@scripts/models/chatbot/PersonalDetail";
import PropertyDetail from "@scripts/models/chatbot/PropertyDetail";
import ChatbotApplication from "@scripts/models/chatbot/ChatbotApplication";

export default {
    mapApplication: (application) => {

        console.log('chatbot application', application);

        const idDetail = new IdDetail(application);
        const personalDetail = new PersonalDetail(application);
        const propertyDetail = new PropertyDetail(application);

        return new ChatbotApplication({
            idDetail: idDetail,
            personalDetails: personalDetail,
            propertyDetails: propertyDetail
        });

    }
}
