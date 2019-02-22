@component('mail::message')
# Package Submitted for Approval

You have received this email because you are a content editor.

You have been invited to check and approve a kiosk package prior to it being deployed to a kiosk.

@component('mail::button', ['url' => config('app.url').'/admin/packages/'.$version->package->id.'#versions-'.$version->id])
View Package
@endcomponent

Thanks,<br>
{{ config('app.name') }} ({{ config('app.env') }})
@endcomponent
