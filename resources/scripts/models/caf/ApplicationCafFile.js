export default class ApplicationCafFile {
    constructor({id, title, first_name, middle_name, last_name, abn, nmi, mirn, business_name, service} = {}) {
        this.id = id;
        this.title = title;
        this.first_name = first_name;
        this.middle_name = middle_name;
        this.last_name = last_name;
        this.abn = abn;
        this.nmi = nmi;
        this.mirn = mirn;
        this.business_name = business_name;
        this.service = service;
    }
}
