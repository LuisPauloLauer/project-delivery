@extends('site.layout_master.site_design')

@section('content')
    <div class="container">
        <div class="row">
            <h4>Finalize sua compra</h4>
        </div>
        <div class="row">
            <form action="{{ route('paypal.pay') }}" method="post">
                @csrf

                <div class="form-row">
                    <div class="col">
                        <button class="btn btn-primary btn-md btn-block" type="submit" value="Submit">PayPal</button>
                    </div>
                </div>

            </form>
        </div>
        <br>
        <div class="row">
            <!-- Set up a container element for the button -->
            <div id="paypal-button-container"></div>
        </div>
    </div>
@endsection

@section('javascript')
    <!-- Include the PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=ATUq9v1cq3HguGrOmOC4AzAUkAIdopp14prhUekoXfvwPUEeFJ0lWsZFEwcFeh4dw7fB989p6l0D_GbB&currency=BRL"></script>

    <script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({

            // Set up the transaction
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '5.00'
                        }
                    }]
                });
            },

            // Finalize the transaction
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Show a success message to the buyer
                    alert('Transaction completed by ' + details.payer.name.given_name + '!');
                });
            }


        }).render('#paypal-button-container');
    </script>
@endsection