<x-guest-layout>

    <style>
        .error-border {
            border: 2px solid red !important;
        }
    </style>

    </style>
    <div class="max-w-7xl bg-gray-100 mx-auto px-4 py-10">

        <!-- BUY NOW PAYMENT POPUP -->
        <div id="paymentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

            <div class="bg-white w-96 rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Processing Payment</h2>

                <p class="text-gray-700 mb-4">
                    Please wait… preparing secure payment.
                </p>

                <div id="paymentStatus" class="text-center py-2 text-blue-600 font-medium">
                    Loading Stripe…
                </div>

                <button onclick="closePaymentModal()"
                    class="mt-4 w-full bg-gray-700 text-white py-2 rounded hover:bg-gray-800">
                    Cancel
                </button>
            </div>
        </div>

        {{-- Breadcrumbs --}}
        <div class="breadcrumbs mb-2 text-sm">
            <a href="{{ route('home') }}" class="hover:underline">Home</a> &gt;
            <a href="{{ route('home', $product->category->id) }}" class=" hover:underline">
                {{ $product->category->name }}</a> &gt;
            <span class="text-gray-500">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- IMAGE SECTION -->
            <div>
                <div class="w-full h-96 bg-gray-100 rounded-lg overflow-hidden">
                    <img id="mainImage" data-image="{{ asset('storage/' . $product->image1_path) }}"
                        src="{{ asset('storage/' . $product->image1_path) }}" class="w-full h-full object-cover">

                </div>

                <!-- Thumbnail Images -->
                <div class="grid grid-cols-4 gap-4 mt-4">
                    @foreach (['image1_path','image2_path','image3_path','image4_path'] as $img)
                    @php $path = $product->$img ? asset('storage/' . $product->$img) : asset('images/no_img.png');
                    @endphp
                    <div class="bg-gray-100 h-24 border rounded-lg overflow-hidden cursor-pointer thumb"
                        data-image="{{ $path }}">
                        <img src="{{ $path }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- PRODUCT INFO -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                <div class="mt-4 flex items-center gap-4">
                    <p class="text-4xl font-semibold text-blue-600">${{ $product->sale_price }}</p>
                    <p class="text-gray-400 line-through">${{ $product->price }}</p>
                </div>

                <p class="mt-6 text-gray-700 leading-relaxed"> {{ $product->short_description }} </p>
                <p class="mt-6 text-gray-700 leading-relaxed"> {!! $product->custom_html !!} </p>

                <!-- Quantity -->
                <div class="mt-6">
                    <label class="block text-gray-700 font-medium mb-2">Quantity</label>
                    <div class="flex items-center border rounded-lg w-32">
                        <button type="button" id="qty-decrease"
                            class="px-3 py-2 text-lg font-bold text-gray-700 hover:bg-gray-200">-</button>
                        <input type="number" id="qty_input" min="1" value="1"
                            class="w-full text-center px-2 py-2 border-gray-100/40">
                        <button type="button" id="qty-increase"
                            class="px-3 py-2 text-lg font-bold text-gray-700 hover:bg-gray-200">+</button>
                    </div>
                </div>

                @php
                $fields = collect($product->fields)->sortBy('order');
                @endphp



                <!-- Dynamic Custom Fields -->
                <div class="custom-fields">

                    <label class="block mb-1 mt-6">Email</label>
                    <input type="email" name="email" id="email" placeholder="yourmail@domain.com" required
                        class="border rounded-lg w-full p-2 mb-3 border-gray-300">

                    @foreach($fields as $field)
                    <div class="mb-3">
                        <label class="block mb-1">{{ $field['label'] }}</label>

                        @if($field['type'] === 'text')
                        <input id="fields[{{ $field['id'] }}]" type="text" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="border rounded-lg w-full p-2 mb-3 border-gray-300" @if($field['required']) required
                            @endif>

                        @elseif($field['type'] === 'number')
                        <input type="number" id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="border rounded-lg w-full p-2 mb-3 border-gray-300" @if($field['required']) required
                            @endif>

                        @elseif($field['type'] === 'textarea')
                        <textarea id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="border rounded-lg w-full p-2 mb-3 border-gray-300" @if($field['required']) required
                            @endif></textarea>

                        @elseif($field['type'] === 'date')
                        <input id="fields[{{ $field['id'] }}]" type="date" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="border rounded-lg w-full p-2 border-gray-300" @if($field['required']) required
                            @endif>

                        @elseif($field['type'] === 'select')
                        <select id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="border rounded-lg w-full p-2  border-gray-300" @if($field['required']) required
                            @endif>
                            @foreach($field['options'] as $option)
                            <option>{{ $option }}</option>
                            @endforeach
                        </select>

                        @elseif($field['type'] === 'radio')
                        @foreach(explode(',', $field['options']) as $option)
                        <label class="mr-3">
                            <input type="radio" id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                                value="{{ trim($option) }}" @if($field['required']) required @endif>
                            {{ trim($option) }}
                        </label>
                        @endforeach

                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-4">
                    <button type="submit" name="action" value="buy"
                        class="bg-blue-600 hover:scale-105 hover:transition-shadow text-white px-4 py-2 rounded">
                        Buy Now
                    </button>

                    <button type="submit" name="action" value="add"
                        class="bg-gray-800 hover:scale-105 hover:transition-shadow text-white px-4 py-2 rounded">
                        Add to Cart
                    </button>
                </div>

                <!-- Extra Details -->
                <div class="mt-10">
                    <h3 class="text-xl font-semibold mb-3">Product Description</h3>
                    <p class="text-gray-700 leading-relaxed"> {{ $product->description }} </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.thumb').forEach(t => {
            t.addEventListener('click', function () {
                let newImg = this.getAttribute('data-image');
                document.getElementById('mainImage').src = newImg;
            });
        });

        const qtyInput = document.getElementById('qty_input');
        const btnIncrease = document.getElementById('qty-increase');
        const btnDecrease = document.getElementById('qty-decrease');

        btnIncrease.addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1;
        });

        btnDecrease.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        });

        // Optional: prevent negative typing
        qtyInput.addEventListener('input', () => {
            if (parseInt(qtyInput.value) < 1 || isNaN(parseInt(qtyInput.value))) {
                qtyInput.value = 1;
            }
        });

        // get all data like fields data and quantity and product slug or id here for further processing on form submission
        /* ======================================================
       COLLECT ALL DATA ON BUTTON CLICK
       ====================================================== */
        function collectProductData(actionType) {

            // Quantity
            let quantity = document.getElementById("qty_input").value;
            let email = document.getElementById("email").value;

            // Dynamic fields
            let fields = {};
            document.querySelectorAll(".custom-fields [id^='fields']").forEach(el => {
                let key = el.getAttribute("name");   // example: fields[email]
                
                if (el.type === "radio") {
                    if (el.checked) fields[key] = el.value;
                } else {
                    fields[key] = el.value;
                }
            });

            // Product ID (safe)
            let productId = "{{ $product->id }}";

            // OR product slug if you prefer:
            // let productSlug = "{{ $product->slug }}";

            let data = {
                product_id: productId,
                quantity: quantity,
                fields: fields,
                action: actionType,
                email: email
            };

            return data;            

        }


        // Attach to Buy Now & Add to Cart buttons

        document.querySelector("button[value='add']").addEventListener("click",  async function () {
            let data = collectProductData();

            const response = await fetch("{{ route('add_to_cart') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            });

            const res = await response.json();

            if (response.ok) {
                alert("Added to cart successfully!");
                console.log(res);
            } else {
                alert("Error: " + res.message);
            }
        });        


        // VALIDATION FUNCTION — REQUIRED FIELDS MUST NOT BE EMPTY
        

        function validateBeforeSubmit() {
            let valid = true;

            // Validate quantity
            let quantity = document.getElementById("qty_input").value;
            let email = document.getElementById("email").value;
            
            if (!quantity || parseInt(quantity) < 1) {
                document.getElementById("qty_input").classList.add("border-red-500");
                valid = false;
            } else {
                document.getElementById("qty_input").classList.remove("border-red-500");
            }

            if (!email || !email.trim()) {
                document.getElementById("email").classList.add("border-red-500");
                valid = false;
            } else {
                document.getElementById("email").classList.remove("border-red-500");
            }

            // Validate required custom fields
            document.querySelectorAll(".custom-fields [id^='fields']").forEach(el => {
                if (el.hasAttribute("required")) {
                    if (el.type === "radio") {
                        let groupChecked = document.querySelector(`input[name="${el.name}"]:checked`);
                        if (!groupChecked) {
                            el.closest('div').classList.add("border-red-500");
                            valid = false;
                        } else {
                            el.closest('div').classList.remove("border-red-500");
                        }
                    } else if (el.value.trim() === "") {
                        el.classList.add("border-red-500");
                        valid = false;
                    } else {
                        el.classList.remove("border-red-500");
                    }
                }
            });

            return valid;
        }



        // Show modal
        function openPaymentModal() {
            document.getElementById("paymentModal").classList.remove("hidden");
            document.getElementById("paymentModal").classList.add("flex");
        }

        // Hide modal
        function closePaymentModal() {
            document.getElementById("paymentModal").classList.add("hidden");
            document.getElementById("paymentModal").classList.remove("flex");
        }

        // Buy Now Button
        document.querySelector("button[value='buy']").addEventListener("click", async function () {
            if (!validateBeforeSubmit()) return;

            let data = collectProductData();

            try {
                const response = await fetch("{{ route('create_checkout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                const res = await response.json();

                if (!response.ok) {
                    alert(res.message || "Something went wrong");
                    return;
                }

                window.location.href = res.redirect_url;

            } catch (err) {
                console.error(err);
            }
        });               

    </script>
</x-guest-layout>