import IdDetail from "@scripts/models/chatbot/IdDetail";
import PersonalDetail from "@scripts/models/chatbot/PersonalDetail";
import PropertyDetail from "@scripts/models/chatbot/PropertyDetail";
import ChatbotApplication from "@scripts/models/chatbot/ChatbotApplication";
import ApplicationNote from "@scripts/models/chatbot/ApplicationNote";

export default {
    mapApplication: (application) => {

        console.log('chatbot application', application.application_notes);

        const id_detail = new IdDetail(application);
        const personal_detail = new PersonalDetail(application);
        const property_detail = new PropertyDetail(application);
        const application_notes = new ApplicationNote(application.application_notes)

        return new ChatbotApplication({
            id_detail: id_detail,
            personal_details: personal_detail,
            property_details: property_detail,
            application_notes : application_notes
        });

    }
}
