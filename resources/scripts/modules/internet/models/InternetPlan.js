export default class InternetPlan {
    constructor({title, name, amount, logo, mbps}) {
        this.title = title || null;
        this.name = name || null;
        this.amount = amount || null;
        this.logo = logo || null;
        this.mbps = mbps || null;
    }
}
