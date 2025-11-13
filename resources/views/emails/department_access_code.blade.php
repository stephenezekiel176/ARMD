<div style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color: #111827;">
    <h2>Hello {{ $fullname }},</h2>

    <p>Thanks for registering with Atommart. Your account has been created and assigned to the <strong>{{ $department }}</strong> department.</p>

    <p>To sign in quickly using your department access flow, use the following special code for your department:</p>

    <p style="font-size: 1.25rem; font-weight: 600;">{{ $special_code }}</p>

    <p>After that, you can sign in at <a href="{{ url('/login?dept=' . $department->id) }}">{{ url('/login?dept=' . $department->id) }}</a> using your full name and the department code.</p>

    <p>If you did not register for this account, please ignore this message.</p>

    <p>Regards,<br>The Atommart Team</p>
</div>
