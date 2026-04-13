<!DOCTYPE html>
<html>
<head>
    <title>New Documentation Request</title>
</head>
<body>
    <h2>New Documentation Request Received</h2>
    <p><strong>Full Name:</strong> {{ $requestData->full_name }}</p>
    <p><strong>Email:</strong> {{ $requestData->email }}</p>
    <p><strong>Company Name:</strong> {{ $requestData->company_name }}</p>
    <p><strong>Document Type:</strong> {{ $requestData->document_type }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $requestData->message }}</p>
    <p>You can manage this request from the admin dashboard.</p>
            <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>{{config('app.name')}} Inc</p>
            <p>{{settings('address')}}</p>
        </div>
</body>
</html>
