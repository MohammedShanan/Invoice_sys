<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="/">Invoice System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
      </div>
    </div>
  </nav>
  </header>

  <RouterView />
</template>

<style scoped>
.navbar-nav .nav-link {
  font-weight: bold;
  transition: color 0.3s ease-in-out;
}

.navbar-nav .nav-link:hover {
  color: #ffdd57;
}
</style>