import IdDetail from "@scripts/models/chatbot/IdDetail";
import PersonalDetail from "@scripts/models/chatbot/PersonalDetail";
import PropertyDetail from "@scripts/models/chatbot/PropertyDetail";
import ChatbotApplication from "@scripts/models/chatbot/ChatbotApplication";

export default {
    mapApplication: (application) => {

        console.log('chatbot application', application);

        const id_detail = new IdDetail(application);
        const personal_detail = new PersonalDetail(application);
        const property_detail = new PropertyDetail(application);

        return new ChatbotApplication({
            id_detail: id_detail,
            personal_details: personal_detail,
            property_details: property_detail
        });

    }
}
