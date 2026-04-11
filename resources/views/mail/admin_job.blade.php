<h3>New Job Application Received</h3>
<p><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Phone:</strong> {{ $data['phone_number'] }}</p>
<p><strong>Position:</strong> {{ $data['position'] ?? 'N/A' }}</p>
<p><strong>Country:</strong> {{ $data['country'] }}</p>
<p><strong>Why NovaVoca:</strong> {{ $data['most_recent_employer'] }}</p>

<p>Note: No resume was attached to this application.</p>
