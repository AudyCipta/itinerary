@push('styles')
  <style>
    header .navbar {
      background-color: rgba(255, 255, 255, 0.9) !important;
      backdrop-filter: blur(10px) !important;
    }

    .nav-link {
      color: #767494 !important;
    }

    .nav-link.active {
      color: #5d5a88 !important;
    }
  </style>
@endpush

<x-layouts.app title="Place Detail">
  <section id="place-detail" class="pt-4 pb-0 mt-5">
    <div class="container">
      <div class="place-detail-content">
        <div class="row">

          <div class="col-md-12">
            <div class="position-relative">
              <div id="carouselExampleFade" class="carousel slide carousel-fade">
                <div class="carousel-indicators">
                  <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                  <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                  <button type="button" data-bs-target="#carouselExampleFade" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                  @php
                    $i = 0;
                  @endphp
                  @foreach ($place->placeImages as $item)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                      <img src="{{ asset('storage/places') }}/{{ $item->picture }}" class="rounded-4 d-block w-100"
                        alt="img">
                    </div>
                  @endforeach
                </div>
                <button class="carousel-control-prev opacity-100" type="button" data-bs-target="#carouselExampleFade"
                  data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next opacity-100" type="button" data-bs-target="#carouselExampleFade"
                  data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
              <h1 class="mb-0 text-light position-absolute"
                style="top: 50%; left: 50%; transform: translate(-50%, -50%);z-index: 9;text-shadow: 2px 3px 5px rgba(0,0,0,0.5);">
                {{ $place->name }}</h1>
            </div>
          </div>

          <div class="col-md">
            <div class="place-detail-content py-4">
              <div class="row">
                <div class="col-md-8">
                  <div class="place-detail-body">
                    {!! $place->description !!}
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="place-detail-info">
                    <h5 class="mb-3">Create a full itinerary - for free!</h5>
                    <a href="#" class="btn btn-primary rounded-pill px-4 py-2" data-bs-toggle="modal"
                      data-bs-target="#tripModal">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-plus" viewBox="0 0 16 16">
                        <path
                          d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                      </svg>
                      Add to Itineraries
                    </a>

                    <div class="google-map mt-4">
                      <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d86062.5882882403!2d115.08356524492159!3d-8.809811681991151!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd24ffc20cb8191%3A0xcb98d1ba7db0495!2sUluwatu%20Temple!5e0!3m2!1sen!2sid!4v1702019739623!5m2!1sen!2sid"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="address mt-4">
                      <p class="mb-0 fw-bold">Address</p>
                      <p>
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path
                              d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                          </svg></span>
                        <span>Balangan, Kuta 80361 Indonesia</span>
                      </p>
                    </div>

                    <div class="ticket mt-4">
                      <p class="mb-0 fw-bold">Ticket</p>
                      <p class="mb-0">
                        <span style="color: #5D5A88"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                            height="16" fill="currentColor" class="bi bi-ticket-detailed" viewBox="0 0 16 16">
                            <path
                              d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M5 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2z" />
                            <path
                              d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5z" />
                          </svg></span>
                        <span class="ms-1" style="color: #5D5A88">Entrance tickets <span
                            class="fw-medium">Rp15.000</span></span>
                      </p>
                    </div>

                    <div class="hour mt-3">
                      <p class="mb-0 fw-bold">Hour</p>
                      <div class="d-flex justify-content-between">
                        <p>Sunday-Monday</p>
                        <p>00.00am - 11.15pm</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- Modal -->
  <div class="modal fade" id="tripModal" tabindex="-1" aria-labelledby="tripModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="tripModalLabel">Your Trip</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row gx-2">
            <div class="col">
              <select class="form-select" aria-label="Default select example">
                <option selected disabled>Choose your trip</option>
              </select>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                  height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
                  <path
                    d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                </svg> Add to My Trip</button>
            </div>
          </div>
          <div class="row mt-3 gx-2">
            <div class="col">
              <input class="form-control" type="text" placeholder="Enter Name of Trip"
                aria-label="default input example">
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg"
                  width="16" height="16" fill="currentColor" class="bi bi-plus-circle me-1"
                  viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                  <path
                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg> Create New Trip</button>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16"
              height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
              <path
                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
            </svg> Add to My Trip</button>
        </div> --}}
      </div>
    </div>
  </div>
</x-layouts.app>
