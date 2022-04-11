<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agent Report</title>
</head>

<body style="font-family: 'Ubuntu', sans-serif">
    <div style="background-color: #532B87; height: 300px; width: 100%; position: relative;">


        <div style="float: left; padding-top: 65px; margin-left: 40%; margin-right: 10px;">
            <img style="height: 80px; width: 80px;" src="{{ asset('assets/images/logo/hood-small.png' )}}" />
        </div>

        <div style="padding-top: 75px; text-align: center; float: left;">
            <div style="color: white; font-weight: bold; font-size: 30px; ">
                HOOD.AI
            </div>
            <div style="color: white; font-size: 22px;">
                It's a Twiddle
            </div>

        </div>

        <div style="clear: both;"></div>

        <div style="color: white; font-size: 28px; text-align: center; font-weight: bold; margin-top: 25px;">
            HOOD RE Agent Report -	{{ $agentName }}
        </div>

        <div style="color: white; font-size: 24px; text-align: center; margin-top: 30px;">
            Office Name-({{ $startDate }} to {{ $endDate }})
        </div>

    </div>

    <div style="border: 1px solid gray; margin-top: 20px; font-weight: bold; width: 45%; float: left; margin-right: 9%;">
        <div style="background-color: #532B87; color: white; padding: 0px 3px; font-size: 20px;">
            Your applications at a glance
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Number of Applications Submitted</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['total_applications_created']}}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Number of Applications with atleast	one	service	connected(other	than water)</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['applications_with_minimum_submitted'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Number of successful water connections</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['successful_water_connections'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Awaiting Confirmation</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['awaiting_confirmation'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Conversion Rate (Succesful Elec Sub/Apps)</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['conversion_rate'] }}%</div>
            <div style="clear: both;"></div>
        </div>

    </div>

    <div style="border: 1px solid gray; margin-top: 20px; font-weight: bold; width: 45%; float: left; ">
        <div style="background-color: #532B87; color: white; padding: 5px 3px; font-size: 20px;">
        Utilities submitted to retailer
        </div>
        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; ">
            <div style="display: inline; float: left; width: 25%; padding-left: 3px; border: 1px solid gray;"></div>
            <div style="display: inline; width: 25%; float:  left; border: 1px solid gray; text-align: center;">Electricity</div>
            <div style="display: inline; width: 24%; float: left; border: 1px solid gray; text-align: center;">Gas</div>
            <div style="display: inline; width: 23%; float: left; border: 1px solid gray; text-align: center;">Water</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; ">
            <div style="display: inline; float: left; width: 25%; padding-left: 3px; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px;">TOTAL</div>
            <div style="display: inline; width: 25%; float:  left; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['electricity'] }}</div>
            <div style="display: inline; width: 24%; float: left; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['gas'] }}</div>
            <div style="display: inline; width: 23%; float: left; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['water'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 20px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 25%; padding-left: 3px; border: 1px solid gray; text-align: center;">TOTAL FUELS</div>
            <div style="display: inline; width: 49%; float:  left; border: 1px solid gray; text-align: center; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['total_energy'] }}</div>
            <div style="display: inline; width: 23%; float:  left; border: 1px solid gray; text-align: center; font-weight: bold; color: #620088; "> - </div>
            <div style="clear: both;"> </div>
        </div>

    </div>

    <div style="clear: both;"></div>


    <div style="border: 1px solid gray; margin-top: 20px; font-weight: bold; ">
        <div style="background-color: #532B87; color: white; padding: 5px 3px; font-size: 20px;">
        Detailed view of your applications
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 10px; font-weight: normal; width: 100%; border: 1px solid gray;">

            <div style="width: 3.5%; height: 40px; padding-left: 5px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold;">
                    App ID
                </div>
            </div>

            <div style="width: 5%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; word-wrap: break-word; text-align: center;">
                Lead Source
                </div>
            </div>

            <div style="width: 9%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Customer Name
                </div>
            </div>

            <div style="width: 7%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Created	Date
                </div>
            </div>

            <div style="width: 8%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Connection Date
                </div>
            </div>

            <div style="width: 14%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Unit No./ full Address??!
                </div>
            </div>
            <div style="width: 6%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Rejection Reason
                </div>
            </div>
            <div style="width: 7%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Customer Type
                </div>
            </div>
            <div style="width: 6%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Elec(1,0)
                </div>
            </div>
            <div style="width: 7%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Elec Status
                </div>
            </div>
            <div style="width: 6%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Water(1,0)
                </div>
            </div>
            <div style="width: 6%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Water Status
                </div>
            </div>
            <div style="width: 6%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Gas(1,0)
                </div>
            </div>
            <div style="width: 6%; height: 40px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                Gas	Status
                </div>
            </div>
            

            <div style="clear: both;"></div>
        </div>

        @foreach ($report['detailedCount'] as $individual)
        <div style="color: black; padding: 0px 0px; font-size: 10px; font-weight: normal; width: 100%; border: 1px solid gray;">

            <div style="width: 3.5%; height: 70px; float: left; border: 1px solid gray; padding-left: 5px">
                <div style="color: black;">
                    {{ $individual['app_id']}}
                </div>
            </div>

            <div style="width: 5%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['lead_source']}}
                </div>
            </div>

            <div style="width: 9%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['customer_name']}}
                </div>
            </div>

            <div style="width: 7%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['created_date']}}
                </div>
            </div>

            <div style="width: 8%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['connection_date']}}
                </div>
            </div>

            <div style="width: 14%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['full_address']}}
                </div>
            </div>

            <div style="width: 6%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['rejection_reason']}}
                </div>
            </div>

            <div style="width: 7%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['customer_type']}}
                </div>
            </div>
            <div style="width: 6%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['is_electricity_submitted']}}       
                </div>
            </div>
            <div style="width: 7%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['electricity_status']}}       
                </div>
            </div>
            <div style="width: 6%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['is_water_submitted']}}       
                </div>
            </div>
            <div style="width: 6%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['water_status']}}       
                </div>
            </div>
            <div style="width: 6%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['is_gas_submitted']}}       
                </div>
            </div>
            <div style="width: 6%; height: 70px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{ $individual['gas_status']}}       
                </div>
            </div>

            <div style="clear: both;"></div>
        </div>
        @endforeach

    </div>

    </div>

</body>

</html>