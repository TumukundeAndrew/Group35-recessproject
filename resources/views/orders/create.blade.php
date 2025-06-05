@extends('layouts.dashboard')

@section('title', 'Create Order')

@section('header', 'Place New Order')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Place New Order</h2>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <select name="product_id" id="product_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}">
                                Sunflower Oil - UGX {{ number_format($product->price, 0) }} per unit
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label for="seller_id" class="block text-sm font-medium text-gray-700 mb-2">Seller</label>
                    <select name="seller_id" id="seller_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Select a seller</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->id }}" {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                {{ $seller->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="1" required
                           value="{{ old('quantity', 1) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                    <div id="total_amount" class="text-2xl font-bold text-gray-900">UGX 0</div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-4 hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const totalAmount = document.getElementById('total_amount');

        function updateTotal() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
            const quantity = parseInt(quantityInput.value) || 0;
            const total = price * quantity;
            totalAmount.textContent = `UGX ${total.toLocaleString()}`;
        }

        productSelect.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
        updateTotal();
    });
</script>
@endpush
@endsection 