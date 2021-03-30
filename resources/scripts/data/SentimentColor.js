export const sentimentColors = {
    NEGATIVE : {
        color: '#E91E63',
        text: 'Bad'
    },
    NEUTRAL : {
        color: '#BDBDBD',
        text: 'Neutral',
    },
    POSITIVE : {
        color: '#4CAF50',
        text: 'Good'
    },
}

export const mapSentimentColor =  key => sentimentColors[key.toUpperCase()].color
export const mapSentiment =  key => sentimentColors[key.toUpperCase()]
