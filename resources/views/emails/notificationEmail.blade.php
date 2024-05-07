<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    {{-- {{dd($approvalData . ' ' . $notificationData)}} --}}
    @isset ($approvalData)
    <p style="font-weight: bold">{{ $approvalData['title'] }}</p>
    <p>{{ $approvalData['body']}}</p>
    <p>Good day! Please be informed that your online application for SERVICE CONNECTION is acknowleged. </p>
    <p>Should you have any queries, please contact LASURECO Consumer Welfare Desk Hotline Number 0968-851-1536.</p>
    <p>Thank you very much.</p>
    @endisset
    @isset($notificationData)
    <p style="font-weight: bold">{{ $notificationData['title'] }}</p>
    <p>{{ $notificationData['body']}}</p>
    <p>Good day! Please be informed that your online application for SERVICE CONNECTION is now on-process for kWh meter installation. </p>
    <p>Should you have any queries, please contact LASURECO Consumer Welfare Desk Hotline Number 0968-851-1536.</p>
    <p>Thank you very much.</p>
    @endisset
</body>
</html>