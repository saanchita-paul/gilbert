import IdDetail from "@scripts/models/chatbot/IdDetail";
import PersonalDetail from "@scripts/models/chatbot/PersonalDetail";
import PropertyDetail from "@scripts/models/chatbot/PropertyDetail";
import ChatbotApplication from "@scripts/models/chatbot/ChatbotApplication";
import ApplicationNote from "@scripts/models/chatbot/ApplicationNote";

export default {
    mapApplication: (application) => {
        const id_detail = new IdDetail(application);
        const personal_detail = new PersonalDetail(application);
        const property_detail = new PropertyDetail(application);
        const application_note = application.application_notes.map(item => new ApplicationNote(item));

        return new ChatbotApplication({
            id_detail: id_detail,
            personal_details: personal_detail,
            property_details: property_detail,
            application_notes : application_note
        });
    }
}
