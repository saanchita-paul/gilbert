export const sentimentColors = {
    NEGATIVE : {
        color: '#E91E63'
    },
    NEUTRAL : {
        color: '#BDBDBD'
    },
    POSITIVE : {
        color: '#4CAF50'
    },
}

export const mapSentimentColor =  key => sentimentColors[key.toUpperCase()].color
