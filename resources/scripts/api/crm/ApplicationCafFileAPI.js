import axios from 'axios';
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";


const BASE_URL = `https://enk2.leninsheikh.com/hood-dashboard/api`;

export default {

    getApplicationCafFileData: async () => {
        try {
            let data =
                [
                    {
                        "id": 6,
                        "title": "Mr.",
                        "first_name": "last_name",
                        "middle_name": null,
                        "last_name": "Satharasinghe",
                        "full_name": "last_name Satharasinghe",
                        "dob": "1990-03-07 00:00:00",
                        "created_date": "2022-06-17",
                        "occupancy_type": "Renter",
                        "phone": "0413672888",
                        "email": "peter@gmail.com",
                        "abn": null,
                        "nmi": null,
                        "mirn": null,
                        "business_name": null,
                        "billing_preference": "email",
                        "to_address": "Dhaka",
                        "service": [],
                        "additional_instruction": null
                    },
                    {
                        "id": 4,
                        "title": "Mr.",
                        "first_name": "test4",
                        "middle_name": null,
                        "last_name": "Satharasinghe",
                        "full_name": "test4 Satharasinghe",
                        "dob": "1980-12-06 00:00:00",
                        "created_date": "2022-06-17",
                        "occupancy_type": "Renter",
                        "phone": "0413672888",
                        "email": "Sam@gmail.com",
                        "abn": "123",
                        "nmi": null,
                        "mirn": null,
                        "business_name": null,
                        "billing_preference": "email",
                        "to_address": " ",
                        "service": [],
                        "additional_instruction": null
                    },
                    {
                        "id": 5,
                        "title": "Mr.",
                        "first_name": "last_name",
                        "middle_name": null,
                        "last_name": "Satharasinghe",
                        "full_name": "last_name Satharasinghe",
                        "dob": "1990-07-02 00:00:00",
                        "occupancy_type": "Owner",
                        "created_date": "2022-06-17",
                        "phone": "0413673888",
                        "email": "Samson@gmail.com",
                        "abn": null,
                        "nmi": null,
                        "mirn": null,
                        "business_name": "business_name",
                        "billing_preference": "email",
                        "to_address": " ",
                        "service": [],
                        "additional_instruction": null
                    },
                    {
                        "id": 3,
                        "title": "Mr.",
                        "first_name": "last_name",
                        "middle_name": null,
                        "last_name": "Satharasinghe",
                        "full_name": "last_name Satharasinghe",
                        "dob": "1989-12-07 00:00:00",
                        "occupancy_type": "Owner",
                        "created_date": "2022-06-17",
                        "phone": "0413671999",
                        "email": "dimi@gmail.com",
                        "abn": null,
                        "nmi": null,
                        "mirn": null,
                        "business_name": "business_name",
                        "billing_preference": "email",
                        "to_address": " ",
                        "service": [],
                        "additional_instruction": null
                    },
                    {
                        "id": 2,
                        "title": "Mr.",
                        "first_name": "hello",
                        "middle_name": null,
                        "last_name": null,
                        "full_name": "hello ",
                        "dob": "1973-03-13 00:00:00",
                        "occupancy_type": "Renter",
                        "created_date": "2022-06-17",
                        "phone": "0423442486",
                        "email": "armin@hummingbird.ai",
                        "abn": "abn",
                        "nmi": null,
                        "mirn": null,
                        "business_name": "business_name",
                        "billing_preference": "email",
                        "to_address": " ",
                        "service": [],
                        "additional_instruction": null
                    },
                    {
                        "id": 1,
                        "title": "Dr.",
                        "first_name": "dimuthu",
                        "middle_name": "hello",
                        "last_name": "test",
                        "full_name": "dimuthu hello test",
                        "dob": "1985-12-06 00:00:00",
                        "occupancy_type": "Renter",
                        "created_date": "2022-06-17",
                        "phone": "0413567333",
                        "email": "dimi@gmail.com",
                        "abn": "994393594703",
                        "nmi": "gol",
                        "mirn": "X123456789",
                        "business_name": "Ray White Camberwell PTY LTD",
                        "billing_preference": "email",
                        "to_address": "Dhaka",
                        "service": [
                            {
                                "id": 1,
                                "service_type": "vvvv",
                                "provider_name": "australia",
                                "plan_type": "test42",
                                "connection_date": "2022-06-17",
                                "status": "done"
                            },
                            {
                                "id": 2,
                                "service_type": "gas",
                                "provider_name": "australia",
                                "plan_type": "jkljkl",
                                "connection_date": "2022-06-17",
                                "status": "wrong"
                            }
                        ],
                        "additional_instruction": null
                    }
                ];
            // const data = await axios.get(`${BASE_URL}/application`);
            // return ApplicationCafFileMapper.mapApplicationCafFileList(data.data.data);
            return ApplicationCafFileMapper.mapApplicationCafFileList(data);
        } catch (error) {
            return error.data;
        }
    },

}
