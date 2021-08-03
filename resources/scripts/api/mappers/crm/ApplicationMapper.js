import Application from "@scripts/models/crm/Application";
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
export default {
    mapApplication(data) {
        let model = Object.assign(new Application(), { ...data });
        return model;
    },
    mapApplicationList(data) {
        const models = [];
        data.forEach((item) => {
            models.push(this.mapApplication(item));
        });
        return models;
    },
    mapApplicationSummary(data) {
        let model = Object.assign(new ApplicationSummary(), { ...data });
        return model;
    },
};
