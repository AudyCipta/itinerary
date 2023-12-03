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

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jwt-decode-module@1.0.4/build/jwt-decode.min.js"></script>
  <script src="https://accounts.google.com/gsi/client" async></script>
  <script>
    function handleCredentialResponse(response) {
      const decoded = new jwt_decode(response.credential);

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        method: "POST",
        url: `{{ route('google.prompt') }}`,
        data: JSON.stringify(decoded),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
          if (response.ok) {
            window.location.reload(true);
          }
        },
        error: function(data) {
          console.error(data);
        }
      });
    }

    window.onload = function() {
      google.accounts.id.initialize({
        client_id: "{{ env('GOOGLE_CLIENT_ID') }}",
        cancel_on_tap_outside: false,
        callback: handleCredentialResponse,
        prompt_parent_id: 'g_id_onload'
      });

      // google.accounts.id.prompt();
    }
  </script>

  <script>
    $(function() {
      let dest = '';
      let pref = '';

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        cache: false
      });

      $('.btn-group').on('change', '.btn-check', function() {
        updateStatusBtnSubmit();
        const id = $(this).attr('id');
        pref = $(`[for=${id}]`).text();
      });

      $('#destination').on('change', function() {
        updateStatusBtnSubmit();
        dest = $("#destination option:selected").text();

        const btnGroup = $(".btn-group");
        btnGroup.empty();

        if ($(this).val()) {
          // $('.btn-check').removeAttr('disabled');

          const id = $(this).val();
          $.ajax({
            method: "POST",
            url: "{{ route('home.get_preference') }}",
            data: {
              id
            },
            dataType: 'json',
            success: function(response) {
              $('#filterPlace button[type=submit]').prop('disabled', true);
              $.each(response.data, function(index, item) {
                const radioInput = $(
                  '<input type="radio" class="btn-check" name="preference" autocomplete="off">');
                radioInput.attr({
                  id: 'btnradio' + item.id,
                  value: item.id
                });

                const label = $('<label class="btn btn-outline-light"></label>');
                label.attr('for', 'btnradio' + item.id);
                label.text(item.name);

                btnGroup.append(radioInput);
                btnGroup.append(label);
              });
            },
            error: function(error) {
              console.log(error);
            }
          });
        }
      });

      function updateStatusBtnSubmit() {
        const selectValue = $('#destination').val();
        const radioSelected = $('input[type=radio]:checked').length > 0;

        if (selectValue && radioSelected) {
          $('#filterPlace button[type=submit]').prop('disabled', false);
        } else {
          $('#filterPlace button[type=submit]').prop('disabled', true);
        }
      }

      $('#filterPlace').on('submit', function(e) {
        e.preventDefault();
        $('#ahpModal').modal('show');

        $('#ahpModal .label_dest').text(dest);
        $('#ahpModal .label_pref').text(pref);

        const placeList = $('.place-list');
        placeList.empty();

        $.ajax({
          method: "POST",
          url: "{{ route('home.filter') }}",
          data: {
            id: $('#filterPlace input[type=radio]:checked').val()
          },
          dataType: 'json',
          beforeSend: function() {
            const skeletonHTML = `
              <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100 rounded-4" aria-hidden="true">
                  <div class="card-body">
                    <svg class="placeholder-glow card-img-top rounded-4 mb-2" width="100%" height="210"
                      xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder"
                      preserveAspectRatio="xMidYMid slice" focusable="false">
                      <title>Placeholder</title>
                      <rect width="100%" height="100%" class="placeholder"></rect>
                    </svg>
                    <h4 class="card-title placeholder-glow">
                      <span class="placeholder col-9"></span>
                    </h4>
                    <p class="card-text placeholder-glow">
                      <span class="placeholder col-3"></span>
                      <span class="placeholder col-4"></span>
                    </p>
                  </div>
                </div>
              </div>
            `;
            placeList.append(skeletonHTML);
            placeList.append(skeletonHTML);
            placeList.append(skeletonHTML);
            placeList.append(skeletonHTML);
            placeList.append(skeletonHTML);
            placeList.append(skeletonHTML);
          },
          success: function(response) {
            setTimeout(function() {
              placeList.empty();
              if (response.data.length) {
                $.each(response.data, function(index, item) {
                  const placeItem = $(`
                  <div class="col-12 col-sm-6 col-lg-4">
                    <a href="#" class="card text-decoration-none rounded-4">
                      <div class="card-body">
                        <img src="https://placehold.co/600x400" class="card-img-top mb-3 rounded-4" alt="places">
                        <h5 class="card-title text-truncate text-dark">${item.name}</h5>
                        <p class="card-text text-secondary">${item.destination_preference.destination_category.name} / ${item.destination_preference.name}</p>
                      </div>
                    </a>
                  </div>
                `);
                  placeList.append(placeItem);
                });
              } else {
                const alert = $(`
                <div class="col-12">
                  <div class="alert alert-primary mb-0" role="alert">
                    Data not found!
                  </div>
                </div>
              `);
                placeList.append(alert);
              }
            }, 800);
          },
          error: function(error) {
            console.log(error);
          }
        });

      });

    });
  </script>
@endpush

<x-layouts.app title="Home Page">
  <section id="home" class="py-0 min-vh-100 bg-secondary d-flex align-items-center">
    <div class="container">
      <div class="home-content">

        <div class="row">
          <div class="col-12 col-md-7">
            <h1 class="text-white">Don't get lost! Plan and make a list of your trip</h1>
            <p class="text-white lead">Explore and create your itenarary for free</p>
          </div>
        </div>

        <div class="row">
          <div class="col-12 col-md-8">
            <div class="card">
              <div class="card-body p-4">
                <form id="filterPlace">
                  <div class="row gx-3 row-gap-3 column-gap-0">
                    <div class="mt-3 mt-md-0 col-lg">
                      <label for="destination" class="form-label">Type of Destination</label>
                      <select name="destination" id="destination" class="form-select">
                        <option value="" selected disabled>Choose your destination</option>
                        @foreach ($destinationCategories as $item)
                          <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mt-3 mt-md-0 col-lg-auto d-flex flex-column">
                      <p class="form-label">Preference</p>
                      <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"
                          disabled>
                        <label class="btn btn-outline-light" for="btnradio1">Beach</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                          disabled>
                        <label class="btn btn-outline-light" for="btnradio2">Cliff</label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
                          disabled>
                        <label class="btn btn-outline-light" for="btnradio3">Mountain</label>
                      </div>
                    </div>
                    <div class="mt-3 mt-md-0 col-lg-auto">
                      <button type="submit" class="btn btn-primary h-100 px-3" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                          class="bi bi-search" viewBox="0 0 16 16">
                          <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section id="trending">
    <div class="container">

      <div class="section-header row justify-content-center">
        <div class="col-12 col-md-7">
          <h2 class="text-center">Trending Destination</h2>
          <p class="text-center lead mb-0">Lorem ipsum dolor sit amet consectetur adipiscing elit semper
            dalar elementum
            tempus hac tellus libero accumsan. </p>
        </div>
      </div>

      <div class="trending-content">
        <div class="row gy-3">
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none rounded-3">
              <div class="card-body">
                <img src="https://placehold.co/800x600" class="card-img-top mb-3 rounded-3" alt="destination">
                <h4 class="card-title">Mobile app</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte. Lorem ipsum
                  dolor sit amet
                  consectetur adipisicing elit.</p>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none rounded-3">
              <div class="card-body">
                <img src="https://placehold.co/800x600" class="card-img-top mb-3 rounded-3" alt="destination">
                <h4 class="card-title">Desktop app</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte. Lorem ipsum
                  dolor sit amet
                  consectetur adipisicing elit.</p>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none rounded-3">
              <div class="card-body">
                <img src="https://placehold.co/800x600" class="card-img-top mb-3 rounded-3" alt="destination">
                <h4 class="card-title">Multiple users</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte. Lorem ipsum
                  dolor sit amet
                  consectetur adipisicing elit.</p>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none rounded-3">
              <div class="card-body">
                <img src="https://placehold.co/800x600" class="card-img-top mb-3 rounded-3" alt="destination">
                <h4 class="card-title">Integrations</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte. Lorem ipsum
                  dolor sit amet
                  consectetur adipisicing elit.</p>
              </div>
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>

  <section id="excellence" class="py-0">
    <div class="container-fluid px-0">
      <div class="excellence-content">

        <div class="row g-0">
          <div class="col-12 col-md-6 order-2 order-md-1">
            <div class="excellence-body">
              <h2 class="mb-3">Boost your productivity with our to-do app</h2>
              <p class="lead">Lorem ipsum dolor sit amet consectetur adipiscing eli mattis sit phasellus
                mollis
                sit
                aliquam sit nullam.</p>

              <div class="feature-list d-flex flex-column row-gap-2">
                <div class="feature-item d-flex align-items-center column-gap-3">
                  <img src="/img/image-placeholder.png" alt="img" class="img-fluid border rounded-circle">
                  <p class="lead mb-0">Lorem ipsum dolor sit</p>
                </div>
                <div class="feature-item d-flex align-items-center column-gap-3">
                  <img src="/img/image-placeholder.png" alt="img" class="img-fluid border rounded-circle">
                  <p class="lead mb-0">Lorem ipsum dolor sit amet </p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-6 order-1 order-md-2">
            <div class="excellence-image">
              <img src="/img/excellence.png" alt="image" class="img-fluid w-100">
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Modal -->
  <div class="modal fade" id="ahpModal" tabindex="-1" aria-labelledby="ahpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="ahpModalLabel">
            <span class="label_dest">Nature</span>:
            <span class="label_pref text-primary">Beach</span>
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row gy-3 place-list" id="place-filter">

            {{-- <div class="col-12 col-sm-6 col-lg-4">
              <div class="card h-100 rounded-4" aria-hidden="true">
                <div class="card-body">
                  <svg class="placeholder-glow card-img-top rounded-4 mb-2" width="100%" height="210"
                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" class="placeholder"></rect>
                  </svg>
                  <h4 class="card-title placeholder-glow">
                    <span class="placeholder col-9"></span>
                  </h4>
                  <p class="card-text placeholder-glow">
                    <span class="placeholder col-3"></span>
                    <span class="placeholder col-4"></span>
                  </p>
                </div>
              </div>
            </div> --}}

            <div class="col-12 col-sm-6 col-lg-4">
              <a href="/places/nusa-penida" class="card text-decoration-none rounded-4">
                <div class="card-body">
                  <img src="https://placehold.co/600x400" class="card-img-top mb-3 rounded-4" alt="places">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">Category / Category</p>
                </div>
              </a>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  @guest
    <div id="g_id_onload" data-client_id="{{ env('GOOGLE_CLIENT_ID') }}" data-login_uri="{{ route('login') }}"
      data-prompt_parent_id="g_id_onload" style="position: fixed; top: 70px; right: 10px; z-index: 1001;">
    </div>
  @endguest
</x-layouts.app>
