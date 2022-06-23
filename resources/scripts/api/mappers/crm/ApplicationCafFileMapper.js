import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";
import COMMISSION from "@scripts/data/constants/COMMISSION";

const mapApplicationCafFileList =  data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapApplicationCafFile(item));
    });
    return values;
}

const mapApplicationCafFile = data => {
    let model = Object.assign(new ApplicationCafFile(), { ...data });
    model.service_type = mapService(data);
    return model;
}

const mapService = data => {
    let test = [];

    data.service.forEach(service => {
        if(service.service_type) {
            test.push({
                service_type: 'test'
            })
        }
    });

    return test;
}

export default {
    mapApplicationCafFileList
}
