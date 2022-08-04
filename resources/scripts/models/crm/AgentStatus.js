export default class AgentStatus {
    constructor({stepCounter, stepName, description, active}) {
        this.stepCounter = stepCounter;
        this.stepName = stepName;
        this.description = description;
        this.active = active;
    }
}
