<div>
    Dear {{ $approver->name }}.
	There is request for leave whose name {{ $user->name }}
	 application from {{ $startDate }} to {{ $endDate }}
	
	Reason : {{ $request->note }}
	
	Please reject or approve in {{ $url }}
	
	Sincerely,
	
	Admin PA Online
	
</div>