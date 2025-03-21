@extends('layouts.guest-app')

@section('content')

    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Contact</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active text-white">Contact</li>
        </ol>
    </div>
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="text-center mx-auto" style="max-width: 700px;">
                            <h1 class="text-primary">Get in touch</h1>
                            <p class="mb-4">Looking for freshly baked goods made with love? At Haydie Bakery, we offer a wide selection of delicious, high-quality pastries, breads, and cakes. Whether you're craving something sweet or need a custom order for a special occasion, we've got you covered. Contact us today for inquiries and orders!</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="h-100 rounded">
                            <iframe class="rounded w-100"
                                    style="height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3870.21642018927!2d121.12025201160218!3d14.064393789920748!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd6fa7ce3adcfd%3A0xfc70988398d9c976!2sHaydie%20Bakery!5e0!3m2!1sen!2sph!4v1739592179110!5m2!1sen!2sph"
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <input type="text" name="name" class="w-100 form-control border-0 py-3 mb-4" placeholder="Your Name" required>
                            <input type="email" name="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="Enter Your Email" required>
                            <textarea name="message" class="w-100 form-control border-0 mb-4" rows="5" cols="10" placeholder="Your Message" required></textarea>
                            <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary" type="submit">Submit</button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <div class="d-flex p-4 rounded mb-4 bg-white">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Address</h4>
                                <p class="mb-2">347F+Q44, Tanauan, Batangas</p>
                            </div>
                        </div>
                        <div class="d-flex p-4 rounded mb-4 bg-white">
                            <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Mail Us</h4>
                                <p class="mb-2">terrenalkimlester@gmail.com</p>
                            </div>
                        </div>
                        <div class="d-flex p-4 rounded bg-white">
                            <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h4>Telephone</h4>
                                <p class="mb-2">0930 670 9774</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
