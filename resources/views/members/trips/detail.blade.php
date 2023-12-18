@push('scripts')
  <script>
    window.onscroll = function() {
      scrollFunction()
    };

    function scrollFunction() {
      let height = window.innerHeight - 60;

      if (document.body.scrollTop > height || document.documentElement.scrollTop > height) {
        document.querySelector('.navbar').classList.add('link-dark');
      } else {
        document.querySelector('.navbar').classList.remove('link-dark');
      }
    }
  </script>

  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.10/index.global.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <script>
    $(function() {
      const currentUrl = window.location.href;
      const id = currentUrl.split('/').pop();

      $.ajax({
        method: "GET",
        url: `{{ route('member.trips.index') }}/${id}/booked`,
        dataType: 'json',
        success: function(response) {
          const calendarEl = document.getElementById('calendar');
          const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: "bootstrap",
            headerToolbar: {
              start: 'prev,next today',
              center: 'title',
              end: 'dayGridMonth,dayGridWeek,dayGridDay,listMonth'
            },
            events: response.data.events
          });
          calendar.render();
        },
        error: function(error) {
          console.log(error);
        }
      });
    });
  </script>
@endpush

<x-layouts.app title="My Trip Detail">
  <section id="detail-trip-hero" class="py-0 min-vh-100 bg-secondary d-flex align-items-center">
    <div class="container text-center">
      <h1 class="text-white">{{ $itineraryBook->total_day }} {{ $itineraryBook->total_day > 1 ? 'days' : 'day' }} with
        {{ $itineraryBook->name }}</h1>
      <p class="text-white lead">{{ date('m d, Y', strtotime($itineraryBook->start_day)) }} -
        {{ date('m d, Y', strtotime($itineraryBook->start_day . ' + ' . $itineraryBook->total_day - 1 . ' days')) }}</p>
    </div>
  </section>

  <section id="detail-trip-calendar">
    <div class="container">
      <div id="calendar"></div>
    </div>
  </section>

  <section id="detail-trip-places" class="pt-0">
    <div class="container">

      <div class="d-flex justify-content-between w-100 mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
          @for ($i = 1; $i <= $itineraryBook->total_day; $i++)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $i == 1 ? 'active' : '' }}" id="pills-home-tab" data-bs-toggle="pill"
                data-bs-target="#day-{{ $i }}" type="button" role="tab" aria-controls="pills-home"
                aria-selected="true">Day {{ $i }}</button>
            </li>
          @endfor
        </ul>
      </div>

      <div class="tab-content" id="pills-tabContent">
        @for ($i = 0; $i < $itineraryBook->total_day; $i++)
          <div class="tab-pane fade {{ $i == 0 ? 'show active' : '' }}" id="day-{{ $i + 1 }}" role="tabpanel"
            aria-labelledby="day-{{ $i + 1 }}-tab" tabindex="0">
            <div class="d-flex flex-column row-gap-4">
              @foreach ($itineraryBook->dayPlaces($i + 1) as $item)
                <div class="card">
                  <div class="card-header d-flex justify-content-between">
                    <span>{{ $item->place->name }} - {{ $item->time }}</span>
                    <div>
                      <a href="#" class="btn btn-sm btn-outline-secondary" data-id="{{ $item->id }}"
                        data-bs-toggle="modal" data-bs-target="#editPlaceModal">Edit</a>
                      <a href="#" class="btn btn-sm btn-outline-secondary" data-id="{{ $item->id }}"
                        data-bs-toggle="modal" data-bs-target="#deletePlaceModal">Delete</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row gx-3">
                      <div class="col-md-4">
                        <img src="{{ asset('storage/places/' . $item->place->placeImages[0]->picture) }}"
                          class="img-fluid rounded-3" alt="places">
                      </div>
                      <div class="col-md-8 d-flex flex-column justify-content-between">
                        <div class="desc">
                          <h5>Description</h5>
                          <p class="card-text text-justify mb-0">{!! substr($item->place->description, 0, 250) !!}...</p>
                        </div>

                        <div class="mb-4">
                          <h5>Address</h5>
                          <p class="card-text">{{ $item->place->name }}</p>
                        </div>

                        <div>
                          <a href="{{ route('places.detail', ['place' => $item->place->slug]) }}"
                            class="btn btn-second text-light">Read More <svg xmlns="http://www.w3.org/2000/svg"
                              width="16" height="16" fill="currentColor" class="bi bi-arrow-right"
                              viewBox="0 0 16 16">
                              <path fill-rule="evenodd"
                                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                            </svg>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endfor
      </div>
    </div>
  </section>

  <!-- Edit Place Modal -->
  <div class="modal fade" id="editPlaceModal" tabindex="-1" aria-labelledby="editPlaceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editPlaceModalLabel">Edit Place</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="row gx-3">
              <div class="col mb-0">
                <label for="time" class="form-label">Day</label>
                <select class="form-select" aria-label="Default select example">
                  <option selected>Open this select menu</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>
              <div class="col-auto mb-0">
                <label for="time" class="form-label">Time</label>
                <input type="time" class="form-control" id="time" name="time">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Place Modal -->
  <div class="modal fade" id="deletePlaceModal" tabindex="-1" aria-labelledby="deletePlaceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="deletePlaceModalLabel">Delete Place</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0 text-muted">Click button "delete" if you want to delete this place.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
