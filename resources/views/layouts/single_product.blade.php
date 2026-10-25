<x-guest-layout>

    <style>
        .error-border {
            border: 2px solid red !important;
        }
        .pdp-input {
            width: 100%;
            border: 1px solid #d8b4fe;
            border-radius: 0.75rem;
            padding: 0.6rem 0.9rem;
            background: #fff;
            transition: box-shadow .15s, border-color .15s;
        }
        .pdp-input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .2);
        }
        .thumb.active { border-color: #7c3aed; box-shadow: 0 0 0 2px rgba(124, 58, 237, .35); }
    </style>

    @php
    $isOnSale = $product->sale_price !== null && (float) $product->sale_price < (float) $product->price;
    $finalPrice = $isOnSale ? $product->sale_price : $product->price;
    $mainImg = $product->image1_path ? asset('storage/' . $product->image1_path) : asset('images/no_img.png');
    $gallery = collect(['image1_path', 'image2_path', 'image3_path', 'image4_path'])
        ->filter(fn ($k) => $product->$k)
        ->map(fn ($k) => asset('storage/' . $product->$k));
    $fields = collect($product->fields)->sortBy('order');
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-8">

        <!-- BUY NOW PAYMENT POPUP -->
        <div id="paymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50">
            <div class="bg-white w-96 max-w-[90vw] rounded-2xl shadow-2xl p-6">
                <h2 class="text-xl font-semibold mb-4">Processing Payment</h2>
                <p class="text-gray-700 mb-4">Please wait&hellip; preparing secure payment.</p>
                <div id="paymentStatus" class="text-center py-2 text-purple-700 font-medium">Loading Stripe&hellip;</div>
                <button onclick="closePaymentModal()"
                    class="mt-4 w-full bg-gray-700 text-white py-2 rounded-xl hover:bg-gray-800">
                    Cancel
                </button>
            </div>
        </div>

        {{-- Breadcrumbs --}}
        <nav class="mb-5 text-sm text-gray-500 flex flex-wrap items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-purple-700">Home</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-purple-700">Shop</a>
            @if ($product->category)
            <span>/</span>
            <span>{{ $product->category->name }}</span>
            @endif
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 bg-white/80 backdrop-blur rounded-3xl shadow-xl p-5 sm:p-8">

            <!-- IMAGE SECTION -->
            <div class="md:sticky md:top-24 self-start">
                <div class="relative w-full aspect-square bg-gradient-to-br from-indigo-50 to-purple-100 rounded-2xl overflow-hidden shadow-inner">
                    <img id="mainImage" data-image="{{ $mainImg }}" src="{{ $mainImg }}"
                        alt="{{ $product->name }}" class="w-full h-full object-contain">
                    @if ($gallery->count() > 1)
                    <button type="button" id="gallery-prev" aria-label="Previous image"
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 shadow text-xl text-indigo-900 hover:bg-white">&#8249;</button>
                    <button type="button" id="gallery-next" aria-label="Next image"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 shadow text-xl text-indigo-900 hover:bg-white">&#8250;</button>
                    <span id="gallery-count" class="absolute bottom-3 right-3 bg-black/60 text-white text-xs px-2 py-1 rounded-full"></span>
                    @endif
                    @if ($isOnSale)
                    <span class="absolute top-3 left-3 bg-amber-400 text-indigo-950 text-xs font-bold px-3 py-1 rounded-full shadow">
                        SAVE ${{ number_format($product->price - $product->sale_price, 2) }}
                    </span>
                    @endif
                </div>

                @if ($gallery->count() > 1)
                <!-- Thumbnail Images -->
                <div class="flex gap-3 mt-4 overflow-x-auto pb-2 snap-x">
                    @foreach ($gallery as $path)
                    <div class="bg-gray-100 w-20 h-20 shrink-0 snap-start border-2 border-transparent rounded-xl overflow-hidden cursor-pointer thumb {{ $loop->first ? 'active' : '' }}"
                        data-image="{{ $path }}">
                        <img src="{{ $path }}" alt="" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- PRODUCT INFO -->
            <div>
                @if ($product->category)
                <span class="inline-block text-xs font-semibold tracking-wide uppercase text-purple-700 bg-purple-100 px-3 py-1 rounded-full">
                    {{ $product->category->name }}
                </span>
                @endif

                <h1 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>

                <div class="mt-4 flex items-baseline gap-3">
                    <p class="text-4xl font-extrabold text-indigo-800">${{ number_format($finalPrice, 2) }}</p>
                    @if ($isOnSale)
                    <p class="text-lg text-gray-400 line-through">${{ number_format($product->price, 2) }}</p>
                    @endif
                </div>

                @if ($product->short_description)
                <p class="mt-5 text-gray-700 leading-relaxed">{{ $product->short_description }}</p>
                @endif
                @if ($product->custom_html)
                <div class="mt-4 text-gray-700 leading-relaxed">{!! $product->custom_html !!}</div>
                @endif

                <hr class="my-6 border-purple-100">

                <!-- Quantity -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Quantity</label>
                    <div class="flex items-center border border-purple-300 rounded-xl w-36 overflow-hidden bg-white">
                        <button type="button" id="qty-decrease"
                            class="px-3 py-2 text-lg font-bold text-gray-700 hover:bg-purple-50">-</button>
                        <input type="number" id="qty_input" min="1" value="1"
                            class="w-full text-center px-2 py-2 border-0 focus:ring-0">
                        <button type="button" id="qty-increase"
                            class="px-3 py-2 text-lg font-bold text-gray-700 hover:bg-purple-50">+</button>
                    </div>
                </div>

                <!-- Dynamic Custom Fields -->
                <div class="custom-fields mt-5">

                    <label class="block mb-1 font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="yourmail@domain.com" required
                        class="pdp-input mb-4">

                    @foreach($fields as $field)
                    <div class="mb-4">
                        <label class="block mb-1 font-medium text-gray-700">{{ $field['label'] }}</label>

                        @if($field['type'] === 'text')
                        <input id="fields[{{ $field['id'] }}]" type="text" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="pdp-input" @if($field['required']) required @endif>

                        @elseif($field['type'] === 'number')
                        <input type="number" id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="pdp-input" @if($field['required']) required @endif>

                        @elseif($field['type'] === 'textarea')
                        <textarea id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]" rows="3"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="pdp-input" @if($field['required']) required @endif></textarea>

                        @elseif($field['type'] === 'date')
                        <input id="fields[{{ $field['id'] }}]" type="date" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="pdp-input" @if($field['required']) required @endif>

                        @elseif($field['type'] === 'select')
                        <select id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                            @if($field['placeholder']) placeholder="{{ $field['placeholder'] }}" @endif
                            class="pdp-input" @if($field['required']) required @endif>
                            @foreach($field['options'] as $option)
                            <option>{{ $option }}</option>
                            @endforeach
                        </select>

                        @elseif($field['type'] === 'radio')
                        <div class="flex flex-wrap gap-x-4 gap-y-2">
                            @foreach(explode(',', $field['options']) as $option)
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" id="fields[{{ $field['id'] }}]" name="fields[{{ $field['name'] }}]"
                                    value="{{ trim($option) }}" class="text-purple-600" @if($field['required']) required @endif>
                                {{ trim($option) }}
                            </label>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if ($product->type == "digital")
                <!-- Digital Product Note -->
                <div class="my-5 rounded-xl bg-indigo-50 border border-indigo-100 p-4">
                    <p class="text-sm text-indigo-900">
                        Note: This is a digital product. After purchase, you will receive a download link via email.
                    </p>

                    @foreach ($product_files as $file)
                    <label class="mt-3 flex items-center gap-2 text-gray-800">
                        <input type="checkbox" class="rounded text-purple-600" name="files[]" id="file_{{ $file['id'] }}">
                        {{ $file['file_name'] }}
                    </label>
                    @endforeach
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-6">
                    <button type="submit" name="action" value="buy"
                        class="w-full bg-gradient-to-r from-indigo-700 to-purple-600 text-white text-lg font-semibold px-6 py-3 rounded-full shadow-lg hover:shadow-xl hover:from-indigo-800 hover:to-purple-700 active:scale-95 transition">
                        Buy Now &middot; ${{ number_format($finalPrice, 2) }}
                    </button>
                    <p class="mt-3 text-center text-xs text-gray-500">&#128274; Secure checkout powered by Stripe</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        const thumbs = Array.from(document.querySelectorAll('.thumb'));
        let galleryIndex = 0;

        function showImage(i) {
            if (!thumbs.length) return;
            galleryIndex = (i + thumbs.length) % thumbs.length;
            document.getElementById('mainImage').src = thumbs[galleryIndex].getAttribute('data-image');
            thumbs.forEach(x => x.classList.remove('active'));
            thumbs[galleryIndex].classList.add('active');
            thumbs[galleryIndex].scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' });
            const counter = document.getElementById('gallery-count');
            if (counter) counter.textContent = (galleryIndex + 1) + ' / ' + thumbs.length;
        }

        thumbs.forEach((t, i) => t.addEventListener('click', () => showImage(i)));
        document.getElementById('gallery-prev')?.addEventListener('click', () => showImage(galleryIndex - 1));
        document.getElementById('gallery-next')?.addEventListener('click', () => showImage(galleryIndex + 1));
        document.addEventListener('keydown', e => {
            if (e.target.matches('input, textarea, select')) return;
            if (e.key === 'ArrowLeft') showImage(galleryIndex - 1);
            if (e.key === 'ArrowRight') showImage(galleryIndex + 1);
        });
        showImage(0);
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
                let key = el.getAttribute("name");

                if (el.type === "radio") {
                    if (el.checked) fields[key] = el.value;
                } else {
                    fields[key] = el.value;
                }
            });

            let productId = "{{ $product->id }}";

            // ⬅️ NEW — Collect selected files
            let selectedFiles = [];
            document.querySelectorAll("input[name='files[]']:checked").forEach(el => {
                selectedFiles.push(el.id.replace("file_", ""));
            });

            let data = {
                product_id: "{{ $product->id }}",
                quantity: quantity,
                fields: fields,
                email: email,
                files: selectedFiles,
                action: actionType
            };

            return data;
        }



        // Attach to Buy Now & Add to Cart buttons

        // document.querySelector("button[value='add']").addEventListener("click",  async function () {
        //     let data = collectProductData();

        //     const response = await fetch("{{ route('add_to_cart') }}", {
        //         method: "POST",
        //         headers: {
        //             "Content-Type": "application/json",
        //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //         },
        //         body: JSON.stringify(data)
        //     });

        //     const res = await response.json();

        //     if (response.ok) {
        //         alert("Added to cart successfully!");
        //         console.log(res);
        //     } else {
        //         alert("Error: " + res.message);
        //     }
        // });        


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

        function validateFiles() {
            let filesChecked = document.querySelectorAll("input[name='files[]']:checked").length;
            return filesChecked > 0;
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

            openPaymentModal();

            let data = collectProductData();

            if ("{{ $product->type }}" === "digital" && !validateFiles()) {
                alert("Please select at least one file.");
                return;
            }

            try {
                const response = await fetch("{{ route('create_checkout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(data)
                });

                const responseText = await response.text();
                let res;

                try {
                    res = JSON.parse(responseText);
                } catch (parseError) {
                    throw new Error(
                        `Server returned HTTP ${response.status} with a non-JSON response: ${responseText.substring(0, 500)}`
                    );
                }

                if (!response.ok) {
                    const validationErrors = res.errors
                        ? Object.values(res.errors).flat().join("\n")
                        : null;
                    const errorMessage = validationErrors || res.message || `Checkout failed (HTTP ${response.status})`;

                    console.error("Checkout request failed:", {
                        status: response.status,
                        response: res
                    });
                    closePaymentModal();
                    alert(errorMessage);
                    return;
                }

                window.location.href = res.redirect_url;

            } catch (err) {
                console.error("Checkout error:", err);
                closePaymentModal();
                alert(err.message || "Unable to start checkout. Check the browser console for details.");
            }
        });               

    </script>
</x-guest-layout>
