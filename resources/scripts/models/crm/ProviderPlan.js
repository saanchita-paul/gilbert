export default class Plan {
    title = null;
    bgColor = null;
    status = false;
    name = '';
    constructor({title ='', bgColor = '', status = false, name= '' }) {
        this.title = title;
        this.bgColor = bgColor;
        this.status = status
        this.name = name;

    }
}
