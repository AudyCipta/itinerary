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
                <form action="">
                  <div class="row gx-3 column-gap-0">
                    <div class="mt-3 mt-md-0 col-md">
                      <label for="destination" class="form-label">Destination</label>
                      <input class="form-control" list="datalistOptions" id="destination"
                        placeholder="Choose destination...">
                      <datalist id="datalistOptions">
                        <option value="Nature">
                        <option value="Cultural">
                        <option value="Entertain">
                      </datalist>
                    </div>
                    <div class="mt-3 mt-md-0 col-md-auto d-flex flex-column">
                      <label for="btncheck1" class="form-label">Preference</label>
                      <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                        <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                        <label class="btn btn-outline-light" for="btncheck1">Beach</label>

                        <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off">
                        <label class="btn btn-outline-light" for="btncheck2">Cliff</label>

                        <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off">
                        <label class="btn btn-outline-light" for="btncheck3">Mountain</label>
                      </div>
                    </div>
                    <div class="mt-3 mt-md-0 col-md-auto">
                      <button class="btn btn-primary h-100 px-3">
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
            <a href="#" class="card text-decoration-none">
              <img src="https://placehold.co/600x400" class="card-img-top" alt="destination">
              <div class="card-body">
                <h4 class="card-title">Mobile app</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte</p>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none">
              <img src="https://placehold.co/600x400" class="card-img-top" alt="destination">
              <div class="card-body">
                <h4 class="card-title">Desktop app</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte</p>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none">
              <img src="https://placehold.co/600x400" class="card-img-top" alt="destination">
              <div class="card-body">
                <h4 class="card-title">Multiple users</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte</p>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <a href="#" class="card text-decoration-none">
              <img src="https://placehold.co/600x400" class="card-img-top" alt="destination">
              <div class="card-body">
                <h4 class="card-title">Integrations</h4>
                <p class="card-text text-muted lead">Lorem ipsum dolor sit amet consecte</p>
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
              <img src="https://placehold.co/600x400" alt="image" class="img-fluid w-100">
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  @guest
    <div id="g_id_onload" data-client_id="{{ env('GOOGLE_CLIENT_ID') }}" data-login_uri="{{ route('login') }}"
      data-prompt_parent_id="g_id_onload" style="position: fixed; top: 70px; right: 10px; z-index: 1001;">
    </div>
  @endguest
</x-layouts.app>
