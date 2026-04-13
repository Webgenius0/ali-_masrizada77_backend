<!DOCTYPE html>
<html>
<head>
    <title>Documentation Request Received</title>
</head>
<body>
    <h2>Hello {{ $requestData->full_name }},</h2>
    <p>Thank you for your interest in our documentation. We have received your request and our team will get back to you shortly.</p>
    <hr>
    <p><strong>Your Request Details:</strong></p>
    <p><strong>Full Name:</strong> {{ $requestData->full_name }}</p>
    <p><strong>Company Name:</strong> {{ $requestData->company_name }}</p>
    <p><strong>Document Requested:</strong> {{ $requestData->document_type }}</p>
    <hr>
    <p>Best regards,<br>
    The Dignity Draw team</p>
            <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>{{config('app.name')}} Inc</p>
            <p>{{settings('address')}}</p>
        </div>
</body>
</html>
