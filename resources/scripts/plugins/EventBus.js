
import Vue from 'vue';

export default class EventHub {
    static get instance() {
        if (!EventHub.eventHubInstance) {
            EventHub.eventHubInstance = new Vue();
        }
        return EventHub.eventHubInstance;
    }
    static eventHubInstance;
}

export const EventBusPlugin = {
    install(vue) {
        this.VueTemp = vue;
        this.VueTemp.prototype.$eventHub = EventHub.instance;
    },
};
