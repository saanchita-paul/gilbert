import {mapSentimentColor} from "@scripts/data/SentimentColor";
const mapColors = labels => {
    return labels.map(label => mapSentimentColor(label.toUpperCase()))
}

const mapLeadSentiment = (data) => {
    let chartLabels = [];
    let chartData = [];
    let sentimentData = [];
    data.forEach(element => {
        chartLabels.push(element.sentiment_text);
        chartData.push(element.percentage);
        sentimentData.push({sentiment_text: element.sentiment_text, value: element.percentage})
    });

    return {
        sentiment: sentimentData,
        chart_data: {
            labels: chartLabels,
            datasets: [{
                borderWidth: 1,
                data: chartData,
                fill: false,
                backgroundColor: mapColors(chartLabels)
            }]
        }
    }
}
export default { mapLeadSentiment }
