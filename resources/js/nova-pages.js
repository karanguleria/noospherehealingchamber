import Profile from './Pages/Profile.vue'
import ChangePassword from './Pages/ChangePassword.vue'

Nova.booting((app, store) => {
  Nova.inertia('Profile', Profile)
  Nova.inertia('ChangePassword', ChangePassword)
})

