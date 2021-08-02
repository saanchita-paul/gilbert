<template>
    <div class="d-flex justify-start">
      <div class="applications-badge">
              <p class="title">My Applications</p>
              <p class="number">10</p>
              <p class="text">Assigned to me</p>
      </div>
      <div class="applications-badge active">
              <p class="title">Unassigned</p>
              <p class="number">50</p>
              <p class="text">Waiting on queue</p>
      </div>
      <div class="applications-badge">
              <p class="title">Assigned</p>
              <p class="number">0</p>
              <p class="text">Being attended</p>
      </div>
      <div class="applications-badge">
              <p class="title">Escalated</p>
              <p class="number">10</p>
              <p class="text">Needs attention</p>
      </div>
      <div class="applications-badge">
              <p class="title">Submitted</p>
              <p class="number">50</p>
              <p class="text">For connection</p>
      </div>
    </div>
</template>

<script>
export default {
  name: "ApplicationsMetrics",
};
</script>

<style scoped>
</style>
