<template>
  <div>
    <Heading class="mb-6">Change Password</Heading>

    <!-- Success Message -->
    <div v-if="status === 'password-updated'" class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded">
      <p class="text-sm font-medium">Password updated successfully!</p>
    </div>

    <!-- General Error Message -->
    <div v-if="formErrors && Object.keys(formErrors).length > 0 && !formErrors.current_password && !formErrors.password && !formErrors.password_confirmation" class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 rounded">
      <p class="text-sm font-medium">Please correct the errors below.</p>
    </div>

    <Card>
      <form @submit.prevent="submitPassword">
        <div class="space-y-6">
          <!-- Current Password -->
          <div>
            <label for="current_password" class="block text-sm font-medium text-white-700 text-white-300 mb-2">
              Current Password
            </label>
            <input
              id="current_password"
              :value="form.current_password || ''"
              @input="form.current_password = $event.target.value"
              type="password"
              required
              autofocus
              class="w-full form-control form-input form-control-bordered"
            />
            <div v-if="formErrors && formErrors.current_password" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ Array.isArray(formErrors.current_password) ? formErrors.current_password[0] : formErrors.current_password }}
            </div>
          </div>

          <!-- New Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-white-700 text-white-300 mb-2">
              New Password
            </label>
            <input
              id="password"
              :value="form.password || ''"
              @input="form.password = $event.target.value"
              type="password"
              required
              class="w-full form-control form-input form-control-bordered"
            />
            <div v-if="formErrors && formErrors.password" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ Array.isArray(formErrors.password) ? formErrors.password[0] : formErrors.password }}
            </div>
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-white-700 text-white-300 mb-2">
              Confirm New Password
            </label>
            <input
              id="password_confirmation"
              :value="form.password_confirmation || ''"
              @input="form.password_confirmation = $event.target.value"
              type="password"
              required
              class="w-full form-control form-input form-control-bordered"
            />
            <div v-if="formErrors && formErrors.password_confirmation" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ Array.isArray(formErrors.password_confirmation) ? formErrors.password_confirmation[0] : formErrors.password_confirmation }}
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end mt-8 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
          <button
            type="submit"
            class="mb-3 border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-primary-500 border-primary-500 hover:[&:not(:disabled)]:bg-primary-400 hover:[&:not(:disabled)]:border-primary-400 text-white dark:text-gray-900"
            :disabled="processing"
          >
            {{ processing ? 'Updating...' : 'Update Password' }}
          </button>
        </div>
      </form>
    </Card>
  </div>
</template>

<script>
export default {
  props: {
    errors: {
      type: Object,
      default: () => ({}),
    },
    status: String,
  },
  data() {
    return {
      form: {
        current_password: '',
        password: '',
        password_confirmation: '',
      },
      processing: false,
    }
  },
  computed: {
    // Access errors from Inertia page props (fallback to prop if not available)
    formErrors() {
      return this.$page?.props?.errors || this.errors || {}
    },
  },
  watch: {
    status(newStatus) {
      // Clear form when password is successfully updated
      if (newStatus === 'password-updated') {
        this.form = {
          current_password: '',
          password: '',
          password_confirmation: '',
        }
      }
    },
    formErrors(newErrors) {
      // Log errors for debugging
      if (newErrors && Object.keys(newErrors).length > 0) {
        console.log('Validation errors:', newErrors)
      }
    },
  },
  methods: {
    submitPassword() {
      this.processing = true
      
      this.$inertia.post('/nova/change-password', this.form, {
        preserveScroll: true,
        onSuccess: (page) => {
          this.processing = false
          // Clear form on success
          if (page.props.status === 'password-updated') {
            this.form = {
              current_password: '',
              password: '',
              password_confirmation: '',
            }
          }
        },
        onError: (errors) => {
          this.processing = false
          console.log('Password update errors:', errors)
        },
        onFinish: () => {
          this.processing = false
        },
      })
    },
  },
}
</script>
