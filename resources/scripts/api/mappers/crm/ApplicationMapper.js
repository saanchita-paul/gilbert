import Application from "@scripts/models/crm/Application";
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
};
