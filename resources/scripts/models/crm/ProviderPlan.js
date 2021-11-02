export default class Plan {
    title = null;
    bgColor = null;
    status = false;
    constructor({title ='', bgColor = '', status = false}) {
        this.title = title;
        this.bgColor = bgColor;
        this.status = status

    }
}
