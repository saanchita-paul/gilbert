export default class Plan {
    title = null;
    bgColor = null;
    status = false;
    name = '';
    type = null;;
    constructor({title ='', bgColor = '', status = false, name= '', type = null}) {
        this.title = title;
        this.bgColor = bgColor;
        this.status = status
        this.name = name;
        this.type = type;
    }
}
