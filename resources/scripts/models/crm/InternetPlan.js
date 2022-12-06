export default class InternetPlan {
    title = null;
    bgColor = null;
    status = false;
    name = '';
    amount = '';
    logo = '';
    constructor({title, bgColor, status, name, amount, logo}) {
        this.title = title;
        this.bgColor = bgColor;
        this.status = status
        this.name = name;
        this.amount = amount;
        this.logo = logo;
    }
}
