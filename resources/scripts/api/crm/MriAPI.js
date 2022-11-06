import axios from "axios";

const dummyData = [
    {
        "key": "4e1df42e-5c53-4762-b07a-79f8d731e0bc:4e477c8d-21b5-42ff-a03c-294b41246885",
        "company_name": "Hood Move Tech 1",
        "activation_date": "2022-06-01T23:59:48.4596523+00:00"
    },
    {
        "key": "4e1df42e-5c53-4762-b07a-79f8d731e0bc:4e477c8d-21b5-42ff-a03c-294b41246884",
        "company_name": "Hood Move Tech 2",
        "activation_date": "2022-06-02T23:59:48.4596523+00:00"
    },
    {
        "key": "4e1df42e-5c53-4762-b07a-79f8d731e0bc:4e477c8d-21b5-42ff-a03c-294b41246883",
        "company_name": "Hood Move Tech 3",
        "activation_date": "2022-06-03T23:59:48.4596523+00:00"
    },
    {
        "key": "4e1df42e-5c53-4762-b07a-79f8d731e0bc:4e477c8d-21b5-42ff-a03c-294b41246882",
        "company_name": "Hood Move Tech 4",
        "activation_date": "2022-06-04T23:59:48.4596523+00:00"
    },
    {
        "key": "4e1df42e-5c53-4762-b07a-79f8d731e0bc:4e477c8d-21b5-42ff-a03c-294b41246881",
        "company_name": "Hood Move Tech 5",
        "activation_date": "2022-06-05T23:59:48.4596523+00:00"
    }
]

const getMriOfficeList = async () => {
    try {
        return dummyData;
        const data = await axios.get('/api/mri-offices');
        return data.data;
    } catch (error) {
        console.log('Error from fetch Mri data');
        return error.data;
    }
}

export default {
    getMriOfficeList
}
