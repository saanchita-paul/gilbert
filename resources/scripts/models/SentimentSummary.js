export default class SentimentSummary {
    constructor({positive_count, negative_count, neutral_count} = {}) {
        this.positive_count = positive_count || 0;
        this.negative_count = negative_count || 0;
        this.neutral_count = neutral_count || 0;
    }
}
