<template>
  <main>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarTogglerDemo01"
        aria-controls="navbarTogglerDemo01"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">Vue.js</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <router-link class="nav-link" exact-active-class="active" to="/"
              >Home</router-link
            >
          </li>
          <li class="nav-item">
            <router-link
              class="nav-link"
              exact-active-class="active"
              to="/index"
              >Productos</router-link
            >
          </li>
          <li class="nav-item">
            <router-link
              class="nav-link"
              exact-active-class="active"
              to="/takePhoto"
              >Take Photo</router-link
            >
          </li>
          <li class="nav-item">
            <router-link
              class="nav-link"
              exact-active-class="active"
              to="/cameraOcr"
              >Image To Text</router-link
            >
          </li>

          <li class="nav-item">
            <router-link
              class="nav-link"
              exact-active-class="active"
              to="/cameraOcr2"
              >Image To Text Static</router-link
            >
          </li>
          
        </ul>
      </div>
    </nav>
    <!-- BODY -->
    <div>
      <router-view></router-view>
    </div>
  </main>
</template>

<script>
export default {
  mounted() {
    console.log("Component APP.");
  },
};
</script>
