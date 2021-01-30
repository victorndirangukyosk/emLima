<template>
  <main>
    <div class="auth-layout-wrap">
      <div class="auth-content">
        <div class="card o-hidden">
          <div id="login-view" class="row">
            <div class="col-md-12">
              <div class="p-4">
                <div class="auth-logo text-center mb-4">
                  <a class="base_url" href='#'>
                    <img
                      :src="logo"
                      class="logo"
                      alt="KwikBasket Logo"
                    />
                  </a>
                </div>
                <h1 class="mb-2 auth-card-header">Log In</h1>
                <form>
                  <div class="form-group">
                    <label for="email">Email address</label>
                    <input
                      required
                      type="email"
                      class="form-control"
                      id="login-email"
                      aria-describedby="emailHelp"
                      autocomplete="off"
                    />
                  </div>
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input
                      required
                      type="password"
                      class="form-control"
                      id="login-password"
                      autocomplete="off"
                    />
                  </div>
                  <button
                    id="login-button"
                    class="btn btn-primary mt-1 btn-block"
                  >
                    LOGIN
                  </button>
                  <input type="hidden" value="0" id="lat" />
                  <input type="hidden" value="0" id="lng" />
                </form>
                <div class="mt-3 text-center">
                  <a
                    href='#'
                    class="btn"
                    >Create An Account</a
                  >
                </div>
                <div class="mt-3 text-center">
                  <a href="#" id="forgot-password-btn" class="text-muted"
                    ><u>Forgot Password?</u></a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container mt-5">
          <div class="row">
            <div class="col-md-12 text-center">
              <a href='#' class="btn"
                >Back To Homepage</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
export default {
  data: () => ({
    logo: require("@/assets/images/logo.svg"),
  }),
};
</script>