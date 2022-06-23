<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Office Report</title>
</head>

<body style="font-family: 'Ubuntu', sans-serif">
    <div style="background-color: #532B87; height: 300px; width: 100%; position: relative;">


        <div style="float: left; padding-top: 65px; margin-left: 36%; margin-right: 10px;">
            <img style="height: 80px; width: 80px;" src="{{ asset('assets/images/logo/hood-border-logo.png' )}}" />
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
            HOOD REA Office Report
        </div>

        <div style="color: white; font-size: 24px; text-align: center; margin-top: 30px;">
            {{ $officeName }} ({{ $startDate }} to {{ $endDate }})
        </div>

    </div>

    <div style="border: 1px solid gray; margin-top: 20px; font-weight: bold; width: 45%; float: left; margin-right: 9%;">
        <div style="background-color: #532B87; color: white; padding: 0px 3px; font-size: 20px;">
            Your office applications at a glance
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Applications Submitted by REA</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['total_applications_created'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Connections Submitted (other than water)</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['applications_with_minimum_submitted'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Water Connections Submitted</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['successful_water_connections'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Awaiting Confirmation</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['awaiting_confirmation'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;">
            <div style="display: inline; float: left; width: 78%; padding-left: 3px; border-right: 1px solid gray;">Conversion Rate</div>
            <div style="text-align: center; float: left; color: #532B87; font-weight: bold; width: 20%; ">{{ $report['totalCount']['conversion_rate'] }}%</div>
            <div style="clear: both;"></div>
        </div>

    </div>


    <div style="margin-top: 20px; font-weight: bold; width: 45%; float: left; ">
        <div style="background-color: #532B87; color: white; padding: 5px 3px; font-size: 20px;">
            Successful Utility Connections
        </div>
        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; ">
            <div style="display: inline;  width: 26%; float: left; border: 1px solid gray; text-align:center">&nbsp;</div>
            <div style="display: inline; width: 25%; float:  left; border: 1px solid gray; text-align: center;">Electricity</div>
            <div style="display: inline; width: 24%; float: left; border: 1px solid gray; text-align: center;">Gas</div>
            <div style="display: inline; width: 23%; float: left; border: 1px solid gray; text-align: center;">Water</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%; ">
            <div style="display: inline; float: left; width: 25%; padding-left: 4px; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px;">Total</div>
            <div style="display: inline; width: 25%; float: left; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['electricity'] }}</div>
            <div style="display: inline; width: 23%; float: left; padding-left: 3px; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['gas'] }}</div>
            <div style="display: inline; width: 23%; float: left; border: 1px solid gray; text-align: center; padding-top: 10px; padding-bottom: 10px; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['water'] }}</div>
            <div style="clear: both;"></div>
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%;">
            <div style="display: inline; float: left; width: 25%; padding-left: 3px; border: 1px solid gray; text-align: center;">Total Fuels</div>
            <div style="display: inline; width: 49%; float: left; padding-left: 3px; border: 1px solid gray; text-align: center; font-weight: bold; color: #620088; ">{{ $report['submittedUtilityCount']['total_energy'] }}</div>
            <div style="display: inline; width: 23%; float:  left; border: 1px solid gray; text-align: center; font-weight: bold; color: #620088; "> - </div>
            <div style="clear: both;"> </div>
        </div>

    </div>

    <div style="clear: both;"></div>


    <div style="margin-top: 20px; font-weight: bold; ">
        <div style="background-color: #532B87; color: white; padding: 5px 3px; font-size: 18px;">
            Detailed view
        </div>

        <div style="color: black; padding: 0px 0px; font-size: 16px; font-weight: normal; width: 100%;">

            <div style="width: 25%; height: 120px; padding-left: 5px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                    Agent Name
                </div>
            </div>

            <div style="width: 14%; height: 120px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; word-wrap: break-word; text-align: center;">
                    Applications Submitted
                </div>
            </div>

            <div style="width: 18%; height: 120px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                    Connections Submitted (other than water)
                </div>
            </div>

            <div style="width: 14%; height: 120px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                    Water Connections Submitted
                </div>
            </div>

            <div style="width: 14%; height: 120px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                    Awaiting Confirmation
                </div>
            </div>

            <div style="width: 13%; height: 120px; padding-left: 2px; float: left; border: 1px solid gray;">
                <div style="color: #532B87; font-weight: bold; text-align: center;">
                    Conversion Rate
                </div>
            </div>

            <div style="clear: both;"></div>
        </div>

        @foreach ($report['detailedCount'] as $agent)
        <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%;">

            <div style="width: 25%; height: 50px; float: left; border: 1px solid gray;  padding-left: 3px">
                <div style="color: black; text-align: center;">
                    {{$agent['agent_name']}}
                </div>
            </div>

            <div style="width: 14%; height: 50px; padding-left: 2px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center; padding-left: 2px;">
                    {{$agent['total_applications_created']}}
                </div>
            </div>

            <div style="width: 18%; height: 50px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{$agent['applications_with_minimum_submitted']}}
                </div>
            </div>

            <div style="width: 14%; height: 50px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{$agent['successful_water_connections']}}
                </div>
            </div>

            <div style="width: 14%; height: 50px; padding-left: 0px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{$agent['awaiting_confirmation']}}
                </div>
            </div>

            <div style="width: 13%; height: 50px; padding-left: 2px; float: left; border: 1px solid gray;">
                <div style="color: black;  text-align: center;">
                    {{$agent['conversion_rate']}}%
                </div>
            </div>

            <div style="clear: both;"></div>
        </div>
        @endforeach

    </div>

    </div>

</body>

</html>
