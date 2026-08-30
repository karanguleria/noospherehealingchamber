import Profile from './Pages/Profile.vue'
import ChangePassword from './Pages/ChangePassword.vue'
import Notifications from './Pages/Notifications.vue'

Nova.booting((app, store) => {
  Nova.inertia('Profile', Profile)
  Nova.inertia('ChangePassword', ChangePassword)
  Nova.inertia('Notifications', Notifications)

  const injectViewAllButton = () => {
    const panels = document.querySelectorAll('.fixed.flex.inset-0.z-20')

    panels.forEach((overlay) => {
      const drawer = overlay.querySelector('.relative.divide-y')
      if (!drawer || drawer.querySelector('[data-view-all-notifications]')) {
        return
      }

      const footer = document.createElement('div')
      footer.setAttribute('data-view-all-notifications', '1')
      footer.className =
        'sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4'

      const button = document.createElement('button')
      button.type = 'button'
      button.className =
        'w-full inline-flex items-center justify-center rounded text-sm font-bold h-9 px-3 shadow bg-primary-500 border border-primary-500 text-white dark:text-gray-900 hover:bg-primary-400'
      button.textContent = 'View all notifications'
      button.addEventListener('click', (event) => {
        event.preventDefault()
        event.stopPropagation()

        try {
          store.commit('nova/toggleNotifications')
        } catch (e) {
          // Panel may already be closing.
        }

        if (window.Nova && typeof window.Nova.visit === 'function') {
          window.Nova.visit('/notifications')
        } else {
          window.location.href = '/nova/notifications'
        }
      })

      footer.appendChild(button)
      drawer.appendChild(footer)
    })
  }

  const observer = new MutationObserver(() => injectViewAllButton())
  observer.observe(document.body, { childList: true, subtree: true })

  document.addEventListener('DOMContentLoaded', injectViewAllButton)
  injectViewAllButton()
})
