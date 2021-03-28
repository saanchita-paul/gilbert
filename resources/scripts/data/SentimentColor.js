export const sentimentColors = {
    NEGATIVE : {
        color: '#E91E63',
        text: 'BAD'
    },
    NEUTRAL : {
        color: '#BDBDBD',
        text: 'Nuetral',
    },
    POSITIVE : {
        color: '#4CAF50',
        text: 'Good'
    },
}

export const mapSentimentColor =  key => sentimentColors[key.toUpperCase()].color
export const mapSentiment =  key => sentimentColors[key.toUpperCase()]
