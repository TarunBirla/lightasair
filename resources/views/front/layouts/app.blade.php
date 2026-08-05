<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Light As Air — Filming & Event Equipment Hire')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @stack('styles')

    <style>
        .whatsapp-float1 {
            position: fixed;
            bottom: 90px;
            right: 25px;
            background-color: #FFC700;
            color: #111;
            border-radius: 50px;
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .25);
            z-index: 1000;
            padding: 10px 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
        }

        .whatsapp-float1:hover {
            background-color: #E6B200;
            color: #111;
            transform: scale(1.05);
        }

        .whatsapp-float {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .25);
            z-index: 1000;
            width: 55px;
            height: 55px;
            line-height: 55px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
        }

        .whatsapp-float:hover {
            background-color: #1eb954;
            color: #fff;
            transform: scale(1.08);
        }

        .request-count {
            background: #111;
            color: #FFC700;
            border-radius: 50%;
            padding: 2px 7px;
            font-size: 12px;
            font-weight: 800;
        }
    </style>
</head>
<body>

@unless(request()->is('login') || request()->is('register'))
@include('front.layouts.navbar')
@endunless

<main>
    @yield('content')
</main>

@unless(request()->is('login') || request()->is('register'))
@include('front.layouts.footer')
@endunless

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@unless(request()->is('login') || request()->is('register'))
<a href="javascript:void(0)"
   onclick="openRequestModal()"
   class="whatsapp-float1"
   title="View your requested items list">
    <div class="d-flex align-items-center gap-1">
        <i class="bi bi-box-seam-fill"></i>
        <span>Request</span>
    </div>
    <span id="requestCount" class="request-count">0</span>
</a>

<a href="https://wa.me/447879175585"
   target="_blank"
   class="whatsapp-float"
   title="Chat on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>
@endunless

{{-- GLOBAL REQUEST MODAL --}}
<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-header bg-warning text-dark border-0 py-3" style="background:#FFC700 !important;">
                <h5 class="modal-title fw-bold m-0 text-dark">
                    <i class="bi bi-box-seam-fill me-1"></i> Submit Product Request
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="item_id">

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:.88rem;">Request Type *</label>
                    <select id="request_product_type" class="form-select fw-bold" style="background:#FAF9F5;border-color:#FFC700;border-radius:10px;">
                        <option value="sell" selected>🟢 Selling Request (Purchase)</option>
                        <option value="rental">🔵 Rental Request (Hire)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:.88rem;">Your Name *</label>
                    <input type="text" id="req_name" class="form-control" placeholder="Enter full name" style="border-radius:10px;">
                    <small class="text-danger" id="name_error"></small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:.88rem;">Email Address *</label>
                    <input type="email" id="req_email" class="form-control" placeholder="name@example.com" style="border-radius:10px;">
                    <small class="text-danger" id="email_error"></small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:.88rem;">Phone / WhatsApp Number *</label>
                    <input type="text" id="req_phone" class="form-control" placeholder="+44 7123 456789" style="border-radius:10px;">
                    <small class="text-danger" id="phone_error"></small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:.88rem;">Additional Instructions / Message</label>
                    <textarea id="req_message" class="form-control" rows="3" placeholder="Specify requirements, dates, or delivery details..." style="border-radius:10px;"></textarea>
                </div>

                <button type="button" class="btn btn-warning w-100 py-2.5 fw-bold shadow-sm" onclick="submitRequest()" style="background:#FFC700;border:none;color:#111;border-radius:10px;font-size:1rem;">
                    <i class="bi bi-whatsapp me-1"></i> Send Request via WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

{{-- GLOBAL TOAST CONTAINER --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index:99999">
    <div id="liveToast" class="toast border-0 shadow-lg" style="border-radius:12px;">
        <div class="toast-header bg-dark text-white" style="border-radius:12px 12px 0 0;">
            <strong class="me-auto" style="color:#FFC700;"><i class="bi bi-bell-fill me-1"></i> Light As AIR</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body bg-white fw-semibold" id="toastMessage"></div>
    </div>
</div>

<script>
function showToast(message) {
    const msgEl = document.getElementById('toastMessage');
    if (msgEl) msgEl.innerHTML = message;
    const toastEl = document.getElementById('liveToast');
    if (toastEl) {
        let toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
    }
}

function openRequestModal(id, title) {
    if (id && title) {
        let requests = JSON.parse(localStorage.getItem('requests')) || [];
        let exists = requests.find(x => x.id == id);
        if (!exists) {
            requests.push({ id: id, title: title });
            localStorage.setItem('requests', JSON.stringify(requests));
            updateRequestCount();
        }
    }

    let requests = JSON.parse(localStorage.getItem('requests')) || [];
    if (requests.length === 0 && !id) {
        showToast('Please select at least one item first.');
        return;
    }

    let modalEl = document.getElementById('requestModal');
    if (modalEl) {
        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function addToRequest(id, title) {
    let requests = JSON.parse(localStorage.getItem('requests')) || [];
    let exists = requests.find(x => x.id == id);

    if (exists) {
        showToast('Item already in your request list.');
    } else {
        requests.push({ id: id, title: title });
        localStorage.setItem('requests', JSON.stringify(requests));
        updateRequestCount();
        showToast('✅ ' + title + ' added to request list.');
    }

    openRequestModal();
}

function updateRequestCount() {
    let requests = JSON.parse(localStorage.getItem('requests')) || [];
    let countElement = document.getElementById('requestCount');
    if (countElement) {
        countElement.innerHTML = requests.length;
    }
}

async function submitRequest() {
    let nameErr = document.getElementById('name_error');
    let emailErr = document.getElementById('email_error');
    let phoneErr = document.getElementById('phone_error');

    if (nameErr) nameErr.innerHTML = '';
    if (emailErr) emailErr.innerHTML = '';
    if (phoneErr) phoneErr.innerHTML = '';

    let nameInput = document.getElementById('req_name') || document.getElementById('name');
    let emailInput = document.getElementById('req_email') || document.getElementById('email');
    let phoneInput = document.getElementById('req_phone') || document.getElementById('phone');
    let messageInput = document.getElementById('req_message') || document.getElementById('message');

    let name = nameInput ? nameInput.value.trim() : '';
    let email = emailInput ? emailInput.value.trim() : '';
    let phone = phoneInput ? phoneInput.value.trim() : '';
    let message = messageInput ? messageInput.value.trim() : '';

    let valid = true;

    if (!name) {
        if (nameErr) nameErr.innerHTML = 'Name is required';
        valid = false;
    }

    if (!email) {
        if (emailErr) emailErr.innerHTML = 'Email is required';
        valid = false;
    } else {
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            if (emailErr) emailErr.innerHTML = 'Enter a valid email address';
            valid = false;
        }
    }

    if (!phone) {
        if (phoneErr) phoneErr.innerHTML = 'Phone number is required';
        valid = false;
    }

    if (!valid) return;

    try {
        let reqType = document.getElementById('request_product_type') ? document.getElementById('request_product_type').value : 'sell';
        let storedItems = JSON.parse(localStorage.getItem('requests')) || [];

        if (storedItems.length === 0 && document.getElementById('item_id') && document.getElementById('item_id').value) {
            storedItems = [{ id: document.getElementById('item_id').value, title: 'Item Request' }];
        }

        const response = await fetch('/guest-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
            },
            body: JSON.stringify({
                items: storedItems,
                product_type: reqType,
                name: name,
                email: email,
                phone: phone,
                message: message
            })
        });

        const data = await response.json();

        if (!data.status) {
            showToast('❌ Request submission failed.');
            return;
        }

        let typeTitle = (reqType === 'rental') ? 'RENTAL REQUEST 🔵' : 'SELLING REQUEST 🟢';
        let msg = `🔥 NEW LIGHT AS AIR REQUEST (${typeTitle})\n\nRequest Type: ${typeTitle}\n\nItems:\n${data.items}\nName: ${data.name}\nEmail: ${data.email}\nPhone: ${data.phone}\nMessage: ${message || 'N/A'}`;

        window.open(`https://wa.me/447879175585?text=${encodeURIComponent(msg)}`, '_blank');

        let modalEl = document.getElementById('requestModal');
        if (modalEl) {
            let bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) bsModal.hide();
        }

        if (nameInput) nameInput.value = '';
        if (emailInput) emailInput.value = '';
        if (phoneInput) phoneInput.value = '';
        if (messageInput) messageInput.value = '';

        localStorage.removeItem('requests');
        updateRequestCount();

        showToast('✅ Request submitted successfully. Opening WhatsApp...');
    } catch (error) {
        console.error(error);
        showToast('❌ Something went wrong. Please try again.');
    }
}

document.addEventListener('DOMContentLoaded', function(){
    updateRequestCount();
});
</script>
</body>
</html>