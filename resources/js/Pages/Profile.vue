<template>
  <div>
    <h1 class="font-normal text-xl md:text-xl mb-3 flex items-center" dusk="index-heading"><span>Profile</span></h1>

    <!-- Success Message -->
    <div v-if="status === 'profile-updated'" class="mb-6 p-4 dark:bg-green-900 border border-transparent rounded">
      <p class="text-sm font-medium border p-4 border-green-900"  style="color: rgba(var(--colors-primary-500))">Profile updated successfully!</p>
    </div>

    <Card>
      <form @submit.prevent="submitProfile">
        <div class="space-y-6">
         

          <!-- First Name and Last Name Row -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="first_name" class="block text-sm font-medium text-white-700 text-white-300 mb-2">
                First Name
              </label>
              <input
                id="first_name"
                :value="form.first_name || user?.first_name || ''"
                @input="form.first_name = $event.target.value"
                type="text"
                class="w-full form-control form-input form-control-bordered"
              />
              <div v-if="errors.first_name" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
                {{ errors.first_name }}
              </div>
            </div>

            <div>
              <label for="last_name" class="block text-sm font-medium text-white-700 text-white-300 mb-2">
                Last Name
              </label>
              <input
                id="last_name"
                :value="form.last_name || user?.last_name || ''"
                @input="form.last_name = $event.target.value"
                type="text"
                class="w-full form-control form-input form-control-bordered"
              />
              <div v-if="errors.last_name" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
                {{ errors.last_name }}
              </div>
            </div>
          </div>

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-white-700 text-white-300 mb-2">
              Email
            </label>
            <input
              id="email"
              :value="form.email || user?.email || ''"
              @input="form.email = $event.target.value"
              type="email"
              required
              class="w-full form-control form-input form-control-bordered"
            />
            <div v-if="errors.email" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ errors.email }}
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between mt-8 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
          <Link href="/change-password" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
            Change Password
          </Link>
          <button
            type="submit"
            class="mb-3 border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&:not(:disabled)]:bg-primary-400 hover:[&:not(:disabled)]:border-primary-400 text-white dark:text-gray-900"
            :disabled="processing"
          >
            {{ processing ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </Card>
  </div>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: null,
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
    status: String,
  },
  data() {
    return {
      form: {
        name: '',
        first_name: '',
        last_name: '',
        email: '',
      },
      processing: false,
      formKey: 0, // Key to force re-render
    }
  },
  computed: {
    userData() {
      return this.user || {}
    },
  },
  watch: {
    user: {
      immediate: true,
      deep: true,
      handler(newUser) {
        console.log('User watcher triggered:', newUser)
        if (newUser && typeof newUser === 'object') {
          this.updateFormFromUser(newUser)
        }
      },
    },
    userData: {
      immediate: true,
      deep: true,
      handler(newUserData) {
        console.log('UserData computed watcher triggered:', newUserData)
        if (newUserData && typeof newUserData === 'object') {
          this.updateFormFromUser(newUserData)
        }
      },
    },
    status(newStatus) {
      console.log('Status changed:', newStatus)
    },
  },
  created() {
    console.log('Component created, user prop:', this.user)
    // Initialize form immediately when component is created
    if (this.user && typeof this.user === 'object') {
      this.updateFormFromUser(this.user)
    }
  },
  mounted() {
    console.log('Component mounted, user prop:', this.user)
    console.log('Form before update:', this.form)
    // Ensure form is initialized when component is mounted
    if (this.user && typeof this.user === 'object') {
      this.updateFormFromUser(this.user)
      console.log('Form after update:', this.form)
    }
  },
  methods: {
    updateFormFromUser(userData) {
      // Get user data from parameter or prop
      if (!userData) {
        userData = this.user
      }
      
      // Check if userData is valid and not null
      if (!userData || typeof userData !== 'object') {
        console.log('No valid user data available')
        return
      }
      
      // Check if userData has any keys
      const keys = Object.keys(userData)
      if (keys.length === 0) {
        console.log('User data object is empty')
        return
      }
      
      console.log('updateFormFromUser called with:', userData)
      
      // Update form fields individually to ensure reactivity
      this.form.name = userData.name || ''
      this.form.first_name = userData.first_name || ''
      this.form.last_name = userData.last_name || ''
      this.form.email = userData.email || ''
      
      // Increment formKey to force re-render of inputs
      this.formKey++
      
      console.log('Form updated:', this.form)
      console.log('Form name value:', this.form.name)
      console.log('Form email value:', this.form.email)
      console.log('FormKey incremented to:', this.formKey)
    },
    submitProfile() {
      this.processing = true
      this.$inertia.post('/nova/profile', this.form, {
        preserveScroll: true,
        onSuccess: () => {
          this.processing = false
        },
        onError: () => {
          this.processing = false
        },
        onFinish: () => {
          this.processing = false
        },
      })
    },
  },
}
</script>
