
import Vue from 'vue';

export default class EventBus {
    static get instance() {
        if (!EventBus.eventHubInstance) {
            EventBus.eventHubInstance = new Vue();
        }
        return EventBus.eventHubInstance;
    }
    static eventHubInstance;
}

export const EventBusPlugin = {
    install(vue) {
        this.VueTemp = vue;
        this.VueTemp.prototype.$eventBus = EventBus.instance;
    },
};
