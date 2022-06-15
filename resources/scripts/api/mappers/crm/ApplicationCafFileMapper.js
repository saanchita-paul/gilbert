import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";

export default {
    mapApplicationCafFileList: data => {
        const values = [];
        data.forEach((item) => {
            values.push(new ApplicationCafFile(item));
        });

        return values;
    },
}
