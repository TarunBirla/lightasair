{{-- resources/views/front/layouts/footer.blade.php --}}

<style>
    .site-footer {
        background: var(--dark);
        color: rgba(255, 255, 255, .7);
        padding: 4rem 0 0;
        font-size: .875rem;
    }

    .site-footer h5 {
        color: var(--white);
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .site-footer h5::after {
        content: '';
        flex: 1;
        height: 2px;
        background: var(--brand);
        border-radius: 2px;
        max-width: 32px;
        margin-left: .5rem;
    }

    .footer-link {
        display: block;
        color: rgba(255, 255, 255, .6);
        text-decoration: none;
        margin-bottom: .55rem;
        transition: color .2s, padding-left .2s;
        font-size: .85rem;
    }

    .footer-link:hover {
        color: var(--brand);
        padding-left: 6px;
    }

    .footer-social a {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, .07);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, .7);
        text-decoration: none;
        transition: background .2s, color .2s;
        font-size: .95rem;
    }

    .footer-social a:hover {
        background: var(--brand);
        color: var(--dark);
    }

    .footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: .7rem;
        margin-bottom: .8rem;
        color: rgba(255, 255, 255, .6);
        font-size: .85rem;
    }

    .footer-contact-item .icon {
        width: 30px;
        height: 30px;
        background: rgba(255, 199, 0, .12);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand);
        flex-shrink: 0;
        font-size: .85rem;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, .08);
        padding: 1.25rem 0;
        margin-top: 3rem;
        font-size: .78rem;
        color: rgba(255, 255, 255, .4);
    }

    .footer-brand-name {
        font-size: 1.4rem;
        font-weight: 900;
        color: var(--white);
        letter-spacing: -.02em;
    }

    .footer-brand-name span {
        color: var(--brand);
    }

    .newsletter-input {
        background: rgba(255, 255, 255, .07);
        border: 1px solid rgba(255, 255, 255, .12);
        color: var(--white);
        border-radius: 8px 0 0 8px;
        padding: .55rem .9rem;
        font-size: .85rem;
        font-family: 'Poppins', sans-serif;
        outline: none;
        flex: 1;
    }

    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, .3);
    }

    .newsletter-btn {
        background: var(--brand);
        color: var(--dark);
        border: none;
        border-radius: 0 8px 8px 0;
        padding: .55rem 1rem;
        font-weight: 700;
        font-size: .85rem;
        cursor: pointer;
        transition: background .2s;
    }

    .newsletter-btn:hover {
        background: var(--brand-dk);
    }
</style>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">

            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                   
                    <div class="footer-brand-name"> <img src="/Logo-3.webp" class="logoData">
                    </div>
                </div>
                <p style="color:rgba(255,255,255,.5);font-size:.85rem;line-height:1.7;">
                    Your trusted partner for premium equipment rental. Quality gear, competitive rates, and outstanding
                    service across the UK.
                </p>
                <div class="footer-social d-flex gap-2 mt-3">
                    <!-- <a href="#"><i class="bi bi-facebook"></i></a> -->
                    <a href="https://www.instagram.com/lightasairuk/" target="_blank"><i
                            class="bi bi-instagram"></i></a>
                    <!-- <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a> -->
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5><i class="bi bi-link-45deg text-warning"></i> Quick Links</h5>
                <a class="footer-link" href="/"><i class="bi bi-chevron-right me-1"
                        style="font-size:.7rem;"></i>Home</a>
                <a class="footer-link" href="/about"><i class="bi bi-chevron-right me-1"
                        style="font-size:.7rem;"></i>About Us</a>
                <a class="footer-link"
                    href="https://static1.squarespace.com/static/6808d7663045f7249c22655f/t/688880c6571c962c399c68f7/1780225841518/Term+and+Condition+of+LAA.pdf"
                    target="_blank"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Terms &
                    Conditions</a>
                <!-- <a class="footer-link" href="/my-bookings"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>My Bookings</a>
                <a class="footer-link" href="/profile"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Profile</a>
                <a class="footer-link" href="/login"><i class="bi bi-chevron-right me-1" style="font-size:.7rem;"></i>Login</a> -->
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h5><i class="bi bi-headset text-warning"></i> Contact Us</h5>
                <div class="footer-contact-item">
                    <div class="icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <span>98a, Terrrace Road,Walton on Thames,Surrey KT12 2EA, United Kingdom</span>
                </div>
                <div class="footer-contact-item">
                    <div class="icon"><i class="bi bi-telephone-fill"></i></div>
                    <span>

                        +44 01932 55 3284

                    </span>
                </div>
                <div class="footer-contact-item">
                    <div class="icon"><i class="bi bi-envelope-fill"></i></div>
                    <span>info@lightasair.co.uk</span>
                </div>

            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h5><i class="bi bi-envelope-paper text-warning"></i> Newsletter</h5>
                <p style="color:rgba(255,255,255,.5);font-size:.83rem;">Get exclusive deals and updates straight to your
                    inbox.</p>
                <div class="d-flex mt-3">
                    <input class="newsletter-input" type="email" placeholder="Your email address…">
                    <button class="newsletter-btn"><i class="bi bi-send-fill"></i></button>
                </div>
                <!-- Trust badges -->
                <div class="d-flex gap-2 mt-4 flex-wrap">
                    <span
                        style="background:rgba(255,199,0,.1);border:1px solid rgba(255,199,0,.2);border-radius:6px;padding:.3rem .65rem;font-size:.72rem;color:rgba(255,255,255,.6);">
                        <i class="bi bi-shield-check text-warning me-1"></i>Secure Payments
                    </span>
                    <span
                        style="background:rgba(255,199,0,.1);border:1px solid rgba(255,199,0,.2);border-radius:6px;padding:.3rem .65rem;font-size:.72rem;color:rgba(255,255,255,.6);">
                        <i class="bi bi-truck text-warning me-1"></i>Fast Delivery
                    </span>
                    <span
                        style="background:rgba(255,199,0,.1);border:1px solid rgba(255,199,0,.2);border-radius:6px;padding:.3rem .65rem;font-size:.72rem;color:rgba(255,255,255,.6);">
                        <i class="bi bi-arrow-counterclockwise text-warning me-1"></i>Easy Returns
                    </span>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span>&copy; 2026 <strong style="color:var(--brand);">Light As AIR</strong>. All rights reserved.</span>
            <div class="d-flex gap-3">
                <!-- <a href="#" style="color:rgba(255,255,255,.4);text-decoration:none;">Privacy Policy</a>
                <a href="#" style="color:rgba(255,255,255,.4);text-decoration:none;">Terms of Service</a>
                <a href="#" style="color:rgba(255,255,255,.4);text-decoration:none;">Cookie Policy</a> -->
            </div>
        </div>
    </div>
</footer>