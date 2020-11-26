import CustomerSummary from "@scripts/models/CustomerSummary";
import ConversationSummary from "@scripts/models/ConversationSummary";
import InfoChart from "@scripts/models/InfoChart";
import SentimentSummary from "@scripts/models/SentimentSummary";

export default {
    toClientList(data) {
        console.log('gf', data);
        const customerSummary = new CustomerSummary();
        const conversationSummary = new ConversationSummary();
        const infoChart = new InfoChart();
        const sentimentSummary = new SentimentSummary()

        customerSummary.total_customer = data.total_customer;
        customerSummary.total_user = data.total_user;
        let totalSentiment = 0;
        let sentiment = {
            neg: 0,
            neu: 0,
            pos: 0
        }

        data.summaries.map(item => {
            totalSentiment += (item.sentiment_negative_count + item.sentiment_neutral_count + item.sentiment_positive_count)
            sentiment.neg += item.sentiment_negative_count;
            sentiment.pos += item.sentiment_positive_count;
            sentiment.neu += item.sentiment_neutral_count;
        });

        if (totalSentiment > 0) {
            const negative_percentage = Math.round((sentiment.neg / totalSentiment) * 100);
            sentimentSummary.negative_count = `${negative_percentage}%`;
            const positive_percentage = Math.round((sentiment.pos / totalSentiment) * 100);
            sentimentSummary.positive_count = `${positive_percentage}%`;
            sentimentSummary.neutral_count =  (100 - negative_percentage - positive_percentage) + '%';
        }

        const graphData = this.mapChart(data.summaries);

        return {
            customerSummary,
            sentimentSummary,
            graphData
        };
    },

    mapChart(data) {
        let total_customer = 0;
        let total_user = 0;
        let days_count = 0;
        let customer_count = 0;
        let user_count = 0;
        let customer_chart = {
            data: [],
            labels: []
        };
        let user_chart = {
            data: [],
            labels: []
        };

        const total_days = data.length;

        switch (true) {
            case (total_days > 365):
                for(let i = 0; i< Math.ceil(total_days/365); i++){
                    for(let j = 0; j<365; j++){
                        if(data[days_count]) {
                            customer_count += data[days_count].total_customer;
                            user_count += data[days_count].total_user;
                            days_count++;
                        }
                    }
                    customer_chart.data.push(customer_count);
                    customer_chart.labels.push('Year '+i);
                    user_chart.data.push(user_count);
                    user_chart.labels.push('Year '+i);
                    customer_count = 0;
                    user_count = 0;
                }
                break;
            case (total_days > 30):
                for(let i = 0; i< Math.ceil(total_days/30); i++){
                    for(let j = 0; j<30; j++){
                        if(data[days_count]) {
                            customer_count += data[days_count].total_customer;
                            user_count += data[days_count].total_user;
                            days_count++;
                        }
                    }
                    customer_chart.data.push(customer_count);
                    customer_chart.labels.push('Month '+i);
                    user_chart.data.push(user_count);
                    user_chart.labels.push('Month '+i);
                    customer_count = 0;
                    user_count = 0;
                }
                break;
            case (total_days > 7):
                for(let i = 0; i< Math.ceil(total_days/7); i++){
                    for(let j = 0; j<7; j++){
                        if(data[days_count]) {
                            customer_count += data[days_count].total_customer;
                            user_count += data[days_count].total_user;
                            days_count++;
                        }
                    }
                    customer_chart.data.push(customer_count);
                    customer_chart.labels.push('Week '+i);
                    user_chart.data.push(user_count);
                    user_chart.labels.push('Week '+i);
                    customer_count = 0;
                    user_count = 0;
                }
                break;
            default:
                for(let i = 0; i< 7; i++){
                    if(data[i]) {
                        customer_chart.data.push(data[i].total_customer);
                        customer_chart.labels.push('Day '+i);
                        user_chart.data.push(data[i].total_user);
                        user_chart.labels.push('Day '+i);
                    }
                }
                break;
          }

        data.map(item => {
            total_customer += item.total_customer;
            total_user += item.total_user;
        });
        return {
            total_customer: total_customer,
            total_user: total_user,
            customer_chart : customer_chart,
            user_chart : user_chart
        }
    }
};
