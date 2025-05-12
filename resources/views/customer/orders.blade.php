<form action="{{ route('orders') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="shipping_address" class="form-label">Shipping Address</label>
        <textarea name="shipping_address" id="shipping_address" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select name="payment_method" id="payment_method" class="form-control">
            <option value="Manual Transfer">Manual Transfer</option>
            <option value="COD">Cash on Delivery</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Place Order</button>
</form>