export default class AppCloseReason {

    // constructor(data) {
    //     console.log("Console in model", data);
    // }

    constructor({ id, value } = {}) {
        this.value = id;
        this.text = value;
            
    }
}