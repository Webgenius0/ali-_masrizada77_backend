<h2>New Contact Request</h2>

<p><strong>Name:</strong> {{ $contact->name }}</p>
<p><strong>Email:</strong> {{ $contact->email }}</p>
<p><strong>Phone:</strong> {{ $contact->phone }}</p>
<p><strong>Company:</strong> {{ $contact->company }}</p>
<p><strong>Message:</strong></p>
<p>{{ $contact->message }}</p>
        <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>{{config('app.name')}} Inc</p>
            <p>{{settings('address')}}</p>
        </div>
