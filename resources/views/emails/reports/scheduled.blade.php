@component('mail::message')
# Your Scheduled Supply Chain Report

Here is your scheduled report for the supply chain system.

@if(isset($content['current_inventory']))
## Current Inventory
@component('mail::table')
| Item | Quantity | Status |
|------|----------|--------|
@foreach($content['current_inventory'] as $item)
| {{ $item->name }} | {{ $item->quantity }} | {{ $item->status }} |
@endforeach
@endcomponent
@endif

@if(isset($content['projected_demand']))
## Projected Demand
@component('mail::table')
| Period | Expected Demand |
|--------|----------------|
@foreach($content['projected_demand'] as $forecast)
| {{ $forecast->date->format('M Y') }} | {{ $forecast->quantity }} |
@endforeach
@endcomponent
@endif

@if(isset($content['regional_sales']))
## Regional Sales Performance
@component('mail::table')
| Region | Sales Volume | Growth |
|--------|--------------|--------|
@foreach($content['regional_sales'] as $sale)
| {{ $sale->region }} | {{ $sale->volume }} | {{ $sale->growth }}% |
@endforeach
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent 