<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div style="background-color: #620088; height: 300px; width: 100%; position: relative;">
    

    <div style="float: left; padding-top: 65px; margin-left: 30%; margin-right: 10px;">
        <img style="height: 100px; width: 100px;"
        src="{{ asset('assets/images/logo/hood-small.png') }}"
        />
    </div>

    <div style="padding-top: 75px; text-align: center; float: left;">
        <div style="color: white; font-weight: bold; font-size: 30px;  ">
             HOOD.AI
        </div>
        <div style="color: white; font-size: 24px;">
             It's a Twiddle
        </div>
        
    </div>

    <div style="clear: both;"></div>
    
    <div style="color: white; font-size: 24px; text-align: center;">
        HOOD REA Corporate Report
   </div>
    
    <div style="color: white; font-size: 24px; text-align: center; margin-top: 40px;">
       {{ $agentName }} ({{ $startDate }} to {{ $endDate }})
    </div>

    </div>

    <div style="border: 1px solid gray; margin-top: 20px; font-weight: bold; ">
            <div style="background-color: #620088; color: white; padding: 5px 3px; font-size: 18px;"> 
                High Level View at the Agency Level 
            </div>
            <div style="color: black; padding: 5px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;"> 
                <div style="display: inline; float: left; width: 80%; padding-left: 3px;">Number of Applications Submitted</div>
                <div style="display: inline; float: left; color: #620088; font-weight: bold;">{{ $report['totalCount']['total_applications_created']}}</div>
                <div style="clear: both;"></div>
            </div>
            <div style="color: black; padding: 5px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;"> 
                <div style="display: inline; float: left; width: 80%; padding-left: 3px;">Number of Applications with atleast one service connected (other than water)</div>
                <div style="display: inline; float: left; color: #620088; font-weight: bold;">{{ $report['totalCount']['applications_with_minimum_submitted']}}</div>
                <div style="clear: both;"></div>
            </div>
            <div style="color: black; padding: 5px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;"> 
                <div style="display: inline; float: left; width: 80%; padding-left: 3px;">Number of successful water connections</div>
                <div style="display: inline; float: left; color: #620088; font-weight: bold;">{{ $report['totalCount']['successful_water_connections']}}</div>
                <div style="clear: both;"></div>
            </div>
            <div style="color: black; padding: 5px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;"> 
                <div style="display: inline; float: left; width: 80%; padding-left: 3px;">Awaiting Confirmation</div>
                <div style="display: inline; float: left; color: #620088; font-weight: bold;">{{ $report['totalCount']['awaiting_confirmation']}}</div>
                <div style="clear: both;"></div>
            </div>
            <div style="color: black; padding: 5px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;"> 
                <div style="display: inline; float: left; width: 80%; padding-left: 3px;">Cancelled Applications</div>
                <div style="display: inline; float: left; color: #620088; font-weight: bold;">{{ $report['totalCount']['cancelled_application']}}</div>
                <div style="clear: both;"></div>
            </div>
            <div style="color: black; padding: 5px 0px; font-size: 18px; font-weight: normal; width: 100%; border: 1px solid gray;"> 
                <div style="display: inline; float: left; width: 80%; padding-left: 3px;">Conversion Rate (Succesful Elec Sub/Apps)</div>
                <div style="display: inline; float: left; color: #620088; font-weight: bold;">{{ $report['totalCount']['conversion_rate']}}</div>
                <div style="clear: both;"></div>
            </div>
    </div>

    <div style="margin-top: 20px; font-weight: bold; ">
            <div style="background-color: #620088; color: white; padding: 5px 3px; font-size: 20px;"> 
                Detailed view of each office and agency 
            </div>

            <!-- Data title section -->
            <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%;"> 
                
                <div style="width: 16%; height: 260px; padding-left: 14px; float: left; border: 1px solid gray;">
                    <div style="color: #620088; font-weight: bold; text-align: left; white-space: nowrap; margin-top: 220px; margin-left: -6px">
                        Office Name
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray;">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Applications received
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Electricity
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Gas
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Water
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Telco
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Internet
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Pay TV
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Awaiting Confirmation
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Cancelled
                    </div>
                </div>

                <div style="width: 8%; height: 260px; padding-left: 2px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; font-weight: bold; -webkit-transform: rotate(90deg); text-align: left; white-space: nowrap; margin-top: 60px; margin-left: -50px;">
                        Convension Rate
                    </div>
                </div>
                
                <div style="clear: both;"></div>
                
            </div>

            <!-- Dynamic data section -->
            @foreach ($report['detailedCount'] as $corporate)
            <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%;"> 
                
                <div style="width: 16%; height: 30px; padding-left: 14px; float: left; border: 1px solid gray;">
                    <div style="color: black; text-align: left; white-space: nowrap; margin-top: 6px; margin-left: -6px;">
                    {{ $individual['office_name']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray;">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['total_applications_created']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['electricityCount']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['gasCount']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['waterCount']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['internetCount']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['awaiting_confirmation']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['cancelled_application']}}
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 2px; float: left; border: 1px solid gray; ">
                    <div style="color: black; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                    {{ $individual['conversion_rate']}}
                    </div>
                </div>
                
                <div style="clear: both;"></div>
            </div>
            @endforeach

            <!-- Footer section -->
            <div style="color: black; padding: 0px 0px; font-size: 18px; font-weight: normal; width: 100%;"> 
                
                <div style="width: 17%; float: left; border: 1px solid gray;">
                    <div style="color: white; background-color: #620088; height: 30px; text-align: center;">
                        Total
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 5px; float: left; border: 1px solid gray;">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                       0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 0px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        0
                    </div>
                </div>

                <div style="width: 8%; height: 30px; padding-left: 2px; float: left; border: 1px solid gray; ">
                    <div style="color: #620088; text-align: right; white-space: nowrap; margin-top: 6px; padding-right: 4px; ">
                        
                    </div>
                </div>
                
                <div style="clear: both;"></div>
                
            </div>
    </div>
</body>
</html>