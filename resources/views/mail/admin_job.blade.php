<h3>New Job Application Received</h3>
<p><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
{{-- <p><strong>Phone:</strong> {{ $data['phone_number'] }}</p> --}}
<p><strong>Position:</strong> {{ $data['position'] ?? 'N/A' }}</p>
{{-- <p><strong>Country:</strong> {{ $data['country'] }}</p> --}}
<p><strong>Why NovaVoca:</strong> {{ $data['most_recent_employer'] }}</p>
        <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>{{config('app.name')}} Inc</p>
            <p>{{settings('address')}}</p>
        </div>
