<template>
  <Card class="flex flex-col items-center justify-center">
    <div class="px-3 py-3">
      <h1 class="text-center text-3xl text-blue-500 font-light">
        <a class="text-primary-500" href="#" @click="copyLink($event)">Invite a New Practitioner (Click to Copy Link)</a>
      </h1>
      
    </div>
  </Card>
</template>

<script>
export default {
  props: [
    'card',
    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  mounted() {
    // Any necessary initialization can be done here
  },

  methods: {
    copyLink(event) {
      event.preventDefault();
      
      const textToCopy = `https://noospherehealingchamber.exponentialhealthcare.com/register-user/${this.card.id}`;
      
      navigator.clipboard.writeText(textToCopy)
        .then(() => {
          alert('Link copied to clipboard!');
        })
        .catch(err => {
          console.error('Failed to copy text: ', err);
        });
    }
  }
}
</script>

<style scoped>
/* Add your styles here */
</style>
