<template>
  <div>
    <h1 class="font-normal text-xl md:text-xl mb-3 flex items-center" dusk="index-heading"><span>Change Password</span></h1>

    <!-- Success Message -->
     <div v-if="status === 'password-updated'" class="mb-6 p-4 dark:bg-green-900 border border-transparent rounded">
      <p class="text-sm font-medium border p-4 border-green-900"  style="color: rgba(var(--colors-primary-500))">Password updated successfully!</p>
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
            <div v-if="errors && errors.current_password" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ Array.isArray(errors.current_password) ? errors.current_password[0] : errors.current_password }}
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
            <div v-if="errors && errors.password" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}
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
            <div v-if="errors && errors.password_confirmation" class="mt-1.5 text-sm text-red-600 dark:text-red-400">
              {{ Array.isArray(errors.password_confirmation) ? errors.password_confirmation[0] : errors.password_confirmation }}
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
    errors(newErrors) {
      // Log errors for debugging
      if (newErrors && Object.keys(newErrors).length > 0) {
        console.log('Validation errors:', newErrors)
      }
    },
  },
  methods: {
    submitPassword() {
      // Check if form fields are filled (check for empty strings and null/undefined)
      const hasCurrentPassword = this.form.current_password && this.form.current_password.trim() !== ''
      const hasPassword = this.form.password && this.form.password.trim() !== ''
      const hasPasswordConfirmation = this.form.password_confirmation && this.form.password_confirmation.trim() !== ''
      
      console.log('Form validation check:', {
        current_password: hasCurrentPassword,
        password: hasPassword,
        password_confirmation: hasPasswordConfirmation,
        form: this.form
      })
      
      if (!hasCurrentPassword || !hasPassword || !hasPasswordConfirmation) {
        console.error('Form validation failed - missing fields', {
          current_password: this.form.current_password,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation
        })
        return
      }
      
      this.processing = true
      console.log('Submitting password form:', {
        current_password: '***',
        password: '***',
        password_confirmation: '***',
        formKeys: Object.keys(this.form)
      })
      
      this.$inertia.post('/nova/change-password', this.form, {
        preserveScroll: true,
        onSuccess: (page) => {
          this.processing = false
          console.log('Password update successful:', page)
          // Form will be cleared by watcher if status is 'password-updated'
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
