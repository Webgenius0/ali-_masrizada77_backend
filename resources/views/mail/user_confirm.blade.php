<p><strong>Hello:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
<p>Thank you for your interest in NovaVoca. We have successfully received your application for the <strong>{{ $data['position'] ?? 'N/A' }}</strong> position.</p>
<p>Our team will review your information and get back to you soon.</p>
<p>Best Regards,<br>NovaVoca Team</p>
        <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>{{config('app.name')}} Inc</p>
            <p>{{settings('address')}}</p>
        </div>
