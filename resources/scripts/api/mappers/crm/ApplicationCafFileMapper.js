import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";

export default {

    mapApplicationCafFileList: data => {
        const values = [];
        data.data.forEach((item) => {
            values.push(this.mapApplicationCafFile(item));
        });
        return values;
    },

    mapApplicationCafFile(data) {
        console.log('inside map data', data);
        let model = Object.assign(new ApplicationCafFile(), { ...data });
        // model.service_type = this.mapService(model.service);
        return model;
    },

    mapService(data) {
        data.forEach(service => {

        });
    },
}
