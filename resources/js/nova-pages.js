import Profile from './Pages/Profile.vue'
import ChangePassword from './Pages/ChangePassword.vue'
import Notifications from './Pages/Notifications.vue'

Nova.booting((app, store) => {
  Nova.inertia('Profile', Profile)
  Nova.inertia('ChangePassword', ChangePassword)
  Nova.inertia('Notifications', Notifications)

  const FOOTER_ATTR = 'data-view-all-notifications'

  const drawerPanel = () =>
    document.querySelector(
      'div.fixed.flex.inset-0.z-20 > .relative.divide-y, div.fixed.flex.inset-0.z-20 > .relative'
    )

  const drawerIsOpen = () => !!document.querySelector('[dusk="notifications-backdrop"]')

  const removeFooters = () => {
    document.querySelectorAll(`[${FOOTER_ATTR}]`).forEach((node) => node.remove())
  }

  const injectViewAllButton = () => {
    if (!drawerIsOpen()) {
      removeFooters()
      return
    }

    const panel = drawerPanel()
    if (!panel) {
      return
    }

    // Keep a single footer pinned at the end of the panel
    const existing = panel.querySelector(`[${FOOTER_ATTR}]`)
    if (existing) {
      if (existing.parentElement !== panel) {
        panel.appendChild(existing)
      }
      return
    }

    document.querySelectorAll(`[${FOOTER_ATTR}]`).forEach((node) => {
      if (node !== existing) {
        node.remove()
      }
    })

    const footer = document.createElement('div')
    footer.setAttribute(FOOTER_ATTR, '1')
    footer.className = 'nhc-notification-footer'
    footer.innerHTML =
      '<button type="button">' +
      'View all notifications' +
      '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>' +
      '</button>'

    footer.querySelector('button').addEventListener('click', (event) => {
      event.preventDefault()
      event.stopPropagation()

      try {
        store.commit('nova/toggleNotifications')
      } catch (e) {
        // Panel may already be closing.
      }

      const backdrop = document.querySelector('[dusk="notifications-backdrop"]')
      if (backdrop) {
        backdrop.click()
      }

      if (window.Nova && typeof window.Nova.visit === 'function') {
        window.Nova.visit('/notifications')
      } else {
        window.location.href = '/nova/notifications'
      }
    })

    panel.appendChild(footer)
  }

  const observer = new MutationObserver(() => injectViewAllButton())
  observer.observe(document.body, { childList: true, subtree: true })

  document.addEventListener('click', () => {
    setTimeout(injectViewAllButton, 30)
  })

  document.addEventListener('DOMContentLoaded', injectViewAllButton)
  injectViewAllButton()
})
